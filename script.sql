
CREATE SCHEMA IF NOT EXISTS lbaw2421;

SET search_path TO lbaw2421;

-----------------------------------------
-- Drop old schema
-----------------------------------------

DROP TABLE IF EXISTS messageTag CASCADE;
DROP TABLE IF EXISTS commentTag CASCADE;
DROP TABLE IF EXISTS report CASCADE;
DROP TABLE IF EXISTS media CASCADE;
DROP TABLE IF EXISTS comment CASCADE;
DROP TABLE IF EXISTS connection CASCADE;
DROP TABLE IF EXISTS directChat CASCADE;
DROP TABLE IF EXISTS postLikes CASCADE;
DROP TABLE IF EXISTS postCategory CASCADE;
DROP TABLE IF EXISTS postTag CASCADE;
DROP TABLE IF EXISTS category CASCADE;
DROP TABLE IF EXISTS post CASCADE;
DROP TABLE IF EXISTS message CASCADE;
DROP TABLE IF EXISTS groupParticipant CASCADE;
DROP TABLE IF EXISTS groups CASCADE;
DROP TABLE IF EXISTS notification CASCADE;
DROP TABLE IF EXISTS users CASCADE;

DROP TYPE IF EXISTS connection_type CASCADE;
DROP TYPE IF EXISTS noti_type CASCADE;
DROP TYPE IF EXISTS user_type CASCADE;
DROP TYPE IF EXISTS post_type CASCADE;

-----------------------------------------
-- User-defined Types
-----------------------------------------

CREATE TYPE connection_type AS ENUM ('BLOCK', 'FOLLOW', 'FRIEND');
CREATE TYPE noti_type AS ENUM ('LIKE', 'FOLLOW_REQUEST', 'GROUP_REQUEST', 'MESSAGE', 'COMMENT', 'INFO', 'TAG');
CREATE TYPE user_type AS ENUM ('NORMAL', 'INFLUENCER');
CREATE TYPE post_type AS ENUM ('TEXT', 'MEDIA');

-----------------------------------------
-- Tables
-----------------------------------------

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    username TEXT NOT NULL UNIQUE,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    photo_url TEXT,
    is_banned BOOLEAN NOT NULL DEFAULT false,
    age INTEGER NOT NULL CHECK (age > 13),
    bio TEXT,
    is_public BOOLEAN NOT NULL,
    typeU user_type NOT NULL DEFAULT 'NORMAL'
);


CREATE TABLE notification (
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    user_id INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    typeN noti_type NOT NULL
);


CREATE TABLE groups (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    description TEXT,
    photo_url TEXT,
    owner_id INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE
);


CREATE TABLE groupParticipant (
    group_id INTEGER NOT NULL REFERENCES groups(id) ON UPDATE CASCADE ON DELETE CASCADE,
    user_id INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    date_joined TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY (group_id, user_id)
);

CREATE TABLE message (
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    date TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL,
    group_id INTEGER REFERENCES groups(id) ON UPDATE CASCADE ON DELETE CASCADE,
    direct_chat BOOLEAN,
    author_id INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE
);

CREATE TABLE messageTag (
    message_id INTEGER NOT NULL REFERENCES message(id) ON UPDATE CASCADE ON DELETE CASCADE,
    tagged_user_id INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (message_id, tagged_user_id)
);

CREATE TABLE post (
    id SERIAL PRIMARY KEY,
    description TEXT,
    is_edited BOOLEAN NOT NULL DEFAULT false,
    is_public BOOLEAN NOT NULL DEFAULT false,
    dateCreation TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL,
    flagged_for_review BOOLEAN NOT NULL DEFAULT false,
    owner_id INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    typeP post_type NOT NULL
);


CREATE TABLE category (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL
);

CREATE TABLE postCategory (
    post_id INTEGER NOT NULL REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE,
    category_id INTEGER NOT NULL REFERENCES category(id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (post_id, category_id)
);

CREATE TABLE postTag (
    post_id INTEGER NOT NULL REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE,
    tagged_user_id INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (post_id, tagged_user_id)
);

CREATE TABLE postLikes (
    post_id INTEGER NOT NULL REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE,
    user_id INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
    PRIMARY KEY (post_id, user_id)
);

CREATE TABLE directChat (
    user1_id INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
    user2_id INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
    dateCreation TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY (user1_id, user2_id)
);


CREATE TABLE connection (
    initiator_user_id INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    target_user_id INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    createdAt TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL,
    typeR connection_type NOT NULL,
    PRIMARY KEY (initiator_user_id, target_user_id)
);


CREATE TABLE comment (
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    is_edited BOOLEAN NOT NULL DEFAULT false,
    post_id INTEGER NOT NULL REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE,
    author_id INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE
);


CREATE TABLE commentTag (
    comment_id INTEGER NOT NULL REFERENCES comment(id) ON UPDATE CASCADE ON DELETE CASCADE,
    tagged_user_id INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (comment_id, tagged_user_id)
);


CREATE TABLE media (
    id SERIAL PRIMARY KEY,
    photo_url TEXT NOT NULL,
    post_id INTEGER NOT NULL REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE report (
    id SERIAL PRIMARY KEY,
    content TEXT,
    createdAt TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL,
    solvedAt TIMESTAMP WITH TIME ZONE,
    comment_id INTEGER REFERENCES comment(id) ON UPDATE CASCADE ON DELETE CASCADE,
    post_id INTEGER REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE,
    target_user_id INTEGER REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    author_id INTEGER REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CHECK (solvedAt IS NULL OR solvedAt > createdAt)
);

-----------------------------------------
-- Triggers and Functions
-----------------------------------------

-- Update Group Owner

CREATE OR REPLACE FUNCTION update_group_owner() 
RETURNS TRIGGER AS $$
DECLARE
    new_owner_id INTEGER;
BEGIN
    IF EXISTS (SELECT 1 FROM groups WHERE owner_id = OLD.id) THEN

        SELECT user_id INTO new_owner_id
        FROM groupParticipant
        WHERE group_id = (SELECT id FROM groups WHERE owner_id = OLD.id)
        AND user_id != OLD.id
        ORDER BY date_joined ASC
        LIMIT 1;

        IF new_owner_id IS NOT NULL THEN
            UPDATE groups
            SET owner_id = new_owner_id
            WHERE owner_id = OLD.id;
        END IF;
    END IF;

    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER update_group_owner_trigger
BEFORE DELETE ON users
FOR EACH ROW
EXECUTE FUNCTION update_group_owner();

-- Convert Follows to Friends

CREATE OR REPLACE FUNCTION check_and_update_to_friend() 
RETURNS TRIGGER AS $$
BEGIN
    IF EXISTS (
        SELECT 1
        FROM connection
        WHERE initiator_user_id = NEW.target_user_id
        AND target_user_id = NEW.initiator_user_id
        AND typeR = 'FOLLOW'
    ) THEN
        UPDATE connection
        SET typeR = 'FRIEND'
        WHERE (initiator_user_id = NEW.initiator_user_id AND target_user_id = NEW.target_user_id)
        OR (initiator_user_id = NEW.target_user_id AND target_user_id = NEW.initiator_user_id);
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_check_and_update_to_friend
AFTER INSERT ON connection
FOR EACH ROW
WHEN (NEW.typeR = 'FOLLOW')
EXECUTE FUNCTION check_and_update_to_friend();


-- Downgrade Friend to Follow

CREATE OR REPLACE FUNCTION check_and_downgrade_friend() 
RETURNS TRIGGER AS $$
BEGIN
    IF EXISTS (
        SELECT 1
        FROM connection
        WHERE initiator_user_id = OLD.target_user_id
        AND target_user_id = OLD.initiator_user_id
        AND typeR = 'FRIEND'
    ) THEN
        UPDATE connection
        SET typeR = 'FOLLOW'
        WHERE initiator_user_id = OLD.target_user_id
        AND target_user_id = OLD.initiator_user_id;
    END IF;

    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_check_and_downgrade_friend
AFTER DELETE ON connection
FOR EACH ROW
WHEN (OLD.typeR = 'FRIEND')
EXECUTE FUNCTION check_and_downgrade_friend();

-- Anonymize User Data

CREATE OR REPLACE FUNCTION anonymize_user_data() 
RETURNS TRIGGER AS $$
BEGIN
    UPDATE users
    SET name = 'DELETED USER',
        username = 'deleted_' || OLD.id,
        email = '',
        password = '',
        photo_url = '',
        bio = '',
        is_banned = true
    WHERE id = OLD.id;

    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER anonymize_user_data_trigger
BEFORE DELETE ON users
FOR EACH ROW
EXECUTE FUNCTION anonymize_user_data();


-- Group Members Limit

CREATE OR REPLACE FUNCTION check_group_members_limit()
RETURNS TRIGGER AS $$
BEGIN
    
    IF (SELECT COUNT(*) FROM groupParticipant WHERE group_id = NEW.group_id) >= 50 THEN
        RAISE EXCEPTION 'A group can have a maximum of 50 members.';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;


CREATE TRIGGER group_members_limit_trigger
BEFORE INSERT ON groupParticipant
FOR EACH ROW
EXECUTE FUNCTION check_group_members_limit();

-- Media Limit for a post

CREATE OR REPLACE FUNCTION check_media_limit()
RETURNS TRIGGER AS $$
BEGIN
    
    IF (SELECT COUNT(*) FROM media WHERE post_id = NEW.post_id) >= 5 THEN
        RAISE EXCEPTION 'A post can have a maximum of 5 media files.';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;


CREATE TRIGGER media_limit_trigger
BEFORE INSERT ON media
FOR EACH ROW
EXECUTE FUNCTION check_media_limit();

-- Notification for the tagged user

CREATE OR REPLACE FUNCTION notify_user_on_comment_tag()
RETURNS TRIGGER AS $$
BEGIN
    
    INSERT INTO notification (content, user_id, typeN)
    VALUES ('You were tagged in a comment.', NEW.tagged_user_id, 'TAG');

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;


CREATE TRIGGER comment_tag_notification_trigger
AFTER INSERT ON commentTag
FOR EACH ROW
EXECUTE FUNCTION notify_user_on_comment_tag();

-----------------------------------------
-- Indexes for Optimizing Query Performance
-----------------------------------------

-- Index on the 'user_id' column in the 'notification' table
CREATE INDEX idx_user_id ON notification USING hash (user_id);

-- Index on the 'author_id' column in the 'message' table
CREATE INDEX idx_author_id ON message USING hash (author_id);

-- Index on the 'post_id' column in the 'comment' table
CREATE INDEX idx_post_id ON comment USING hash (post_id); 

-----------------------------------------
-- Full-Text Search Setup for 'post' Table
-----------------------------------------

ALTER TABLE post ADD COLUMN tsvectors TSVECTOR;

CREATE OR REPLACE FUNCTION post_search_update() RETURNS TRIGGER AS $$
BEGIN
    NEW.tsvectors := (
        setweight(to_tsvector('english', NEW.description), 'A') || 
        setweight(
            to_tsvector('english', 
                COALESCE((SELECT string_agg(content, ' ') FROM comment WHERE post_id = NEW.id), '')
            ), 
            'B'
        )
    );
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER post_search_update
BEFORE INSERT OR UPDATE ON post
FOR EACH ROW
EXECUTE FUNCTION post_search_update();

CREATE INDEX post_search_idx ON post USING GIN (tsvectors);

-----------------------------------------
-- Full-Text Search Setup for 'group' Table
-----------------------------------------

-- Step 1: Add a 'tsvectors' column to the 'group' table to store the computed ts_vectors
ALTER TABLE groups
ADD COLUMN tsvectors TSVECTOR;

CREATE OR REPLACE FUNCTION group_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors := setweight(to_tsvector('english', NEW.name), 'A');
    ELSIF TG_OP = 'UPDATE' THEN
        IF (NEW.name <> OLD.name) THEN
            NEW.tsvectors := setweight(to_tsvector('english', NEW.name), 'A');
        END IF;
    END IF;
    RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER group_search_update
BEFORE INSERT OR UPDATE ON groups
FOR EACH ROW
EXECUTE PROCEDURE group_search_update();

CREATE INDEX groups_search_idx ON groups USING GIN (tsvectors);