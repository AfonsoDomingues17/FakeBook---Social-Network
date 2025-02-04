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

document.querySelectorAll('button.timeline').forEach(button => {
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
}
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
}

document.querySelectorAll('#search_options [id^="search-"]').forEach(button => {
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

function previewSentPicture(event) {
  const reader = new FileReader();
  reader.onload = function () {
      const output = document.getElementById('image-preview');
      output.src = reader.result;
      output.style.display = 'block';
  };
  reader.readAsDataURL(event.target.files[0]);
}

const toggleButton = document.getElementById('toggleDropdown');
  const dropdown = document.getElementById('DropdownMore');
  if(toggleButton && dropdown){
  toggleButton.addEventListener('click', function() {
    if (dropdown.style.display === 'none' || dropdown.style.display === '') {
      dropdown.style.display = 'block';
    } else {
      dropdown.style.display = 'none';
    }
  });

  // Optional: Close the dropdown if clicked outside
  document.addEventListener('click', function(event) {
    if (!toggleButton.contains(event.target) && !dropdown.contains(event.target)) {
      dropdown.style.display = 'none';
    }
  });
  }
const toggleButton2 = document.getElementById('toggleDropdown2');
const dropdown2 = document.getElementById('DropdownMore2');
if(toggleButton2 && dropdown2){
toggleButton2.addEventListener('click', function() {
  if (dropdown2.style.display === 'none' || dropdown2.style.display === '') {
    dropdown2.style.display = 'block';
  } else {
    dropdown2.style.display = 'none';
  }
});

// Optional: Close the dropdown if clicked outside
document.addEventListener('click', function(event) {
  if (!toggleButton2.contains(event.target) && !dropdown2.contains(event.target)) {
    dropdown2.style.display = 'none';
  }
});

}