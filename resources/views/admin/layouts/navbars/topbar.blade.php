
<style>
    .navbar {
  min-height: 53px;
  padding-bottom: 0;
  padding-top: 0;
  padding:1%;
  z-index: 4;
  height: 6vh;
}
    </style>
<div class="navbar">
    <div>
        <button class="navbar-toggler toggle-button" id="myButton" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
        
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
        </button>
    </div>
    <div>
    <div class="d-flex align-items-center">
      <!-- Avatar -->
      <div class="dropdown p-1" style="border:1px solid black; border-radius:22px">
      <img
            src="{{asset('img/profile.svg')}}"
            class="rounded-circle"
            height="35"
            alt="Black and White Portrait of a Man"
            loading="lazy"
          />
        
        <!-- <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar">
    
          <li>
            <a class="dropdown-item" href="#">My profile</a>
          </li>
          <li>
            <a class="dropdown-item" href="#">Settings</a>
          </li>
          <li>
            <a class="dropdown-item" href="#">Logout</a>
          </li>
        </ul> -->
      </div>
    </div>
</div>
</div>

<script>



var mainContent = document.querySelector('.main-panel');
var toggleButton = document.querySelector('.toggle-button');
var sidebar = document.querySelector('.sidebar');

function handleResize() {
    var screenWidth = window.innerWidth;
    
    if (screenWidth >= 991) {
      if (sidebar.classList.contains('show')) {
        sidebar.style.width='15%';
    }}
    else
    {
      sidebar.style.width='50%';
    }
}

window.addEventListener('resize', handleResize);

var computedStyles = window.getComputedStyle(sidebar);

toggleButton.addEventListener('click', function() {
  if (window.innerWidth <= 991) {
    if (computedStyles.getPropertyValue('transform')==='none') {
      sidebar.style.transform = 'translate3d(260px, 0, 0)';
      sidebar.style.width = '0%';
    }
    else{
      sidebar.style.transform = 'none';
      sidebar.style.width = '50%';
    }
   
  }
  else{
    if (!sidebar.classList.contains('show')) {
        mainContent.style.width = 'calc(100% - 15%)'; // Adjust the width accordingly
        sidebar.style.width='15%'
        sidebar.classList.add('show');
    } else {
      sidebar.classList.remove('show');
        mainContent.style.width = '100%';
        sidebar.style.width='0%'
    }
  }
});
</script>


