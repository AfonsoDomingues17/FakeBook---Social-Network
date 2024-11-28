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








