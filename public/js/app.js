function addEventListeners() {
    let itemCheckers = document.querySelectorAll('article.card li.item input[type=checkbox]');
    [].forEach.call(itemCheckers, function(checker) {
      checker.addEventListener('change', sendItemUpdateRequest);
    });
  
    let itemCreators = document.querySelectorAll('article.card form.new_item');
    [].forEach.call(itemCreators, function(creator) {
      creator.addEventListener('submit', sendCreateItemRequest);
    });
  
    let itemDeleters = document.querySelectorAll('article.card li a.delete');
    [].forEach.call(itemDeleters, function(deleter) {
      deleter.addEventListener('click', sendDeleteItemRequest);
    });
  
    let cardDeleters = document.querySelectorAll('article.card header a.delete');
    [].forEach.call(cardDeleters, function(deleter) {
      deleter.addEventListener('click', sendDeleteCardRequest);
    });
  
    let cardCreator = document.querySelector('article.card form.new_card');
    if (cardCreator != null)
      cardCreator.addEventListener('submit', sendCreateCardRequest);
  }
  
  function encodeForAjax(data) {
    if (data == null) return null;
    return Object.keys(data).map(function(k){
      return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
  }
  
  function sendAjaxRequest(method, url, data, handler) {
    let request = new XMLHttpRequest();
  
    request.open(method, url, true);
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.addEventListener('load', handler);
    request.send(encodeForAjax(data));
  }
  
  function sendItemUpdateRequest() {
    let item = this.closest('li.item');
    let id = item.getAttribute('data-id');
    let checked = item.querySelector('input[type=checkbox]').checked;
  
    sendAjaxRequest('post', '/api/item/' + id, {done: checked}, itemUpdatedHandler);
  }
  
  function sendDeleteItemRequest() {
    let id = this.closest('li.item').getAttribute('data-id');
  
    sendAjaxRequest('delete', '/api/item/' + id, null, itemDeletedHandler);
  }
  
  function sendCreateItemRequest(event) {
    let id = this.closest('article').getAttribute('data-id');
    let description = this.querySelector('input[name=description]').value;
  
    if (description != '')
      sendAjaxRequest('put', '/api/cards/' + id, {description: description}, itemAddedHandler);
  
    event.preventDefault();
  }
  
  function sendDeleteCardRequest(event) {
    let id = this.closest('article').getAttribute('data-id');
  
    sendAjaxRequest('delete', '/api/cards/' + id, null, cardDeletedHandler);
  }
  
  function sendCreateCardRequest(event) {
    let name = this.querySelector('input[name=name]').value;
  
    if (name != '')
      sendAjaxRequest('put', '/api/cards/', {name: name}, cardAddedHandler);
  
    event.preventDefault();
  }
  
  function itemUpdatedHandler() {
    let item = JSON.parse(this.responseText);
    let element = document.querySelector('li.item[data-id="' + item.id + '"]');
    let input = element.querySelector('input[type=checkbox]');
    element.checked = item.done == "true";
  }
  
  function itemAddedHandler() {
    if (this.status != 200) window.location = '/';
    let item = JSON.parse(this.responseText);
  
    // Create the new item
    let new_item = createItem(item);
  
    // Insert the new item
    let card = document.querySelector('article.card[data-id="' + item.card_id + '"]');
    let form = card.querySelector('form.new_item');
    form.previousElementSibling.append(new_item);
  
    // Reset the new item form
    form.querySelector('[type=text]').value="";
  }
  
  function itemDeletedHandler() {
    if (this.status != 200) window.location = '/';
    let item = JSON.parse(this.responseText);
    let element = document.querySelector('li.item[data-id="' + item.id + '"]');
    element.remove();
  }
  
  function cardDeletedHandler() {
    if (this.status != 200) window.location = '/';
    let card = JSON.parse(this.responseText);
    let article = document.querySelector('article.card[data-id="'+ card.id + '"]');
    article.remove();
  }
  
  function cardAddedHandler() {
    if (this.status != 200) window.location = '/';
    let card = JSON.parse(this.responseText);
  
    // Create the new card
    let new_card = createCard(card);
  
    // Reset the new card input
    let form = document.querySelector('article.card form.new_card');
    form.querySelector('[type=text]').value="";
  
    // Insert the new card
    let article = form.parentElement;
    let section = article.parentElement;
    section.insertBefore(new_card, article);
  
    // Focus on adding an item to the new card
    new_card.querySelector('[type=text]').focus();
  }
  
  function createCard(card) {
    let new_card = document.createElement('article');
    new_card.classList.add('card');
    new_card.setAttribute('data-id', card.id);
    new_card.innerHTML = `
  
    <header>
      <h2><a href="cards/${card.id}">${card.name}</a></h2>
      <a href="#" class="delete">&#10761;</a>
    </header>
    <ul></ul>
    <form class="new_item">
      <input name="description" type="text">
    </form>`;
  
    let creator = new_card.querySelector('form.new_item');
    creator.addEventListener('submit', sendCreateItemRequest);
  
    let deleter = new_card.querySelector('header a.delete');
    deleter.addEventListener('click', sendDeleteCardRequest);
  
    return new_card;
  }
  
  function createItem(item) {
    let new_item = document.createElement('li');
    new_item.classList.add('item');
    new_item.setAttribute('data-id', item.id);
    new_item.innerHTML = `
    <label>
      <input type="checkbox"> <span>${item.description}</span><a href="#" class="delete">&#10761;</a>
    </label>
    `;
  
    new_item.querySelector('input').addEventListener('change', sendItemUpdateRequest);
    new_item.querySelector('a.delete').addEventListener('click', sendDeleteItemRequest);
  
    return new_item;
  }

  addEventListeners();
  
function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

const type = getQueryParam('type') || 'public';

const publicButton = document.getElementById('public');
const followingButton = document.getElementById('following');

if(publicButton && followingButton){
  if (type === 'public') {
      publicButton.style.borderBottom = "5px solid #007bff"; 
      followingButton.style.borderBottom = "none";       
  } else if (type === 'following') {
      followingButton.style.borderBottom = "5px solid #007bff"; 
      publicButton.style.borderBottom = "none";              
  }
}
document.querySelectorAll('button').forEach(button => {
    button.addEventListener('click', () => {
        publicButton.style.borderBottom = "none";
        followingButton.style.borderBottom = "none";
        if (button.id === 'public') {
            publicButton.style.borderBottom = "5px solid #007bff";
        } else if (button.id === 'following') {
            followingButton.style.borderBottom = "5px solid #007bff";
        }
    });
});

function getQueryParam(param) {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(param);
}

const type2 = getQueryParam('type') || 'users';

const usersButton = document.getElementById('search-users');
const postsButton = document.getElementById('search-posts');

function highlightButton(button) {
  if(button){
  usersButton.style.borderBottom = "none";
  postsButton.style.borderBottom = "none";
  button.style.borderBottom = "5px solid #007bff";
  }
}

if (type2 === 'users') {
  highlightButton(usersButton);
} else if (type2 === 'posts') {
  highlightButton(postsButton);
} else if (type2 === 'groups') {
  highlightButton(groupsButton);
}

document.querySelectorAll('#timeline_options button').forEach(button => {
  button.addEventListener('click', () => {
      highlightButton(button);
  });
});

document.addEventListener('DOMContentLoaded', function() {
  const dropdownToggle = document.getElementById('dropdown-toggle');
  const dropdownMenu = document.querySelector('#account-options .dropdown');
  if(!dropdownToggle || !dropdownMenu) return;
  dropdownToggle.addEventListener('click', function() {
      dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
  });

  // Optional: Close the dropdown when clicking outside of it
  document.addEventListener('click', function(event) {
      if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
          dropdownMenu.style.display = 'none';
      }
  });
});

document.addEventListener('DOMContentLoaded', function () {
  function initializeDynamicModals() {
      document.querySelectorAll('[data-bs-toggle="modal"]').forEach(button => {
          const modalId = button.getAttribute('data-bs-target');
          const modalElement = document.querySelector(modalId);
          if (modalElement) {
              bootstrap.Modal.getOrCreateInstance(modalElement);
          }
      });
  }

  initializeDynamicModals();

  document.body.addEventListener('click', function (e) {
      if (e.target.classList.contains('edit-post-btn')) {
          const postId = e.target.getAttribute('data-post-id');
          const modalId = e.target.getAttribute('data-bs-target');
          const modalElement = document.querySelector(modalId);

          if (modalElement) {
              const modalInstance = bootstrap.Modal.getOrCreateInstance(modalElement);
              modalInstance.show();
          }
      }
  });

  const observer = new MutationObserver(() => {
      initializeDynamicModals();
  });

  observer.observe(document.body, { childList: true, subtree: true });
});


document.addEventListener('shown.bs.modal', (event) => {
  // Get all siblings of the modal
  const modals = document.querySelectorAll('.modal');
  const siblings = [...document.body.children].filter(
      (child) => !child.contains(modals[0]) && child.tagName !== 'SCRIPT'
  );

  // Set inert attribute on all siblings
  siblings.forEach((sibling) => sibling.setAttribute('inert', ''));
});

document.addEventListener('hidden.bs.modal', (event) => {
  // Remove inert attribute from siblings
  const siblings = document.querySelectorAll('[inert]');
  siblings.forEach((sibling) => sibling.removeAttribute('inert'));
});