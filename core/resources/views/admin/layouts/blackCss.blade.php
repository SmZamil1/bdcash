
<style>
    body {
        background-color: #ffeedc;
    }

    a {
        color: rgb(0, 0, 0);
    }

    a span {
        color: rgb(0, 0, 0);
    }

    .text-primary {
        color: rgb(0, 0, 0) !important;
    }

    .login-main {
        background-color: #ff9f43;
        background-image: url("{{ asset('assets/admin/images/login-2.png') }}");
        /* Full height */
        height: 100%;

        /* Center and scale the image nicely */
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        opacity: 100;
    }

    .login-main::before {
        background-color: #e9ecef00;
        /* background-image: url("{{ asset('assets/admin/images/login-2.png') }}"); */

        /* Full height */
        height: 100%;

        /* Center and scale the image nicely */
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        opacity: 100;
    }

    .login-area::after {
        position: absolute;
        content: '';
        width: 47px;
        height: 47px;
        right: -80px;
        top: -100px;
        border: 40px solid rgb(52 34 229 / 0%);
        box-sizing: border-box;
        filter: blur(2px);
        border-radius: 50%;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        -ms-border-radius: 50%;
        -o-border-radius: 50%;
    }

    .login-wrapper__top {
        background-color: #e65b1800;
    }

    .login-wrapper__top::after {
        border-color: #3d2bfb00 transparent transparent transparent;
    }

    .login-wrapper {
        background-color: #1e157d00;
        background: rgb(28 220 248 / 5%);
        box-shadow: 0 8px 32px 0 #ffc1074a;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 10px;
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        -ms-border-radius: 10px;
        -o-border-radius: 10px;
        overflow: hidden;
    }
</style>
 <script src="{{asset('assets/templates/invester/js/main.js')}}"></script>
<style>
    /* all */
    .bg--dark {
        background-color: #e55a14 !important;
        background-image: radial-gradient(circle farthest-corner at 10% 20%, #e24c00 0%, #ffb569 90%);
    }

    .bg--primary {
        background-color: #e65b1800 !important;
        background-image: radial-gradient(circle farthest-corner at 10% 20%, #e24c00 0%, #ffb569 90%) !important;
    }

    .bg--success {
        background-color: #e65b1800 !important;
        background-image: radial-gradient(circle farthest-corner at 10% 20%, #baf95e 0%, #00650f 90%) !important;
    }

    .bg--warning {
        background-color: #e65b1800 !important;
        background-image: radial-gradient(circle farthest-corner at 10% 20%, #ffb569 0%, #e24c00 90%) !important;
    }

    .bg--pink {
        background-color: #e65b1800 !important;
        background-image: radial-gradient(circle farthest-corner at 10% 20%, #ff69be 0%, #e2007c 90%) !important;
    }

    .bg--1 {
        background-color: #e65b1800 !important;
        background-image: radial-gradient(circle farthest-corner at 10% 20%, #69b9ff 0%, #005ae2 90%) !important;
    }
    .bg--6 {
        background-color: #e65b1800 !important;
        background-image: radial-gradient(circle farthest-corner at 10% 20%, #af69ff 0%, #9700e2 90%) !important;
    }
    .bg--17 {
        background-color: #e65b1800 !important;
        background-image: radial-gradient(circle farthest-corner at 10% 20%, #268e00 0%, #88e200 90%) !important;
    }

    .btn--primary {
        background-color: #e65b1800 !important;
        background-image: radial-gradient(circle farthest-corner at 10% 20%, #e24c00 0%, #ffb569 90%) !important;
    }

    .btn--primary:hover {
        background-color: #e65b1800 !important;
        background-image: radial-gradient(circle farthest-corner at 10% 20%, #575757 0%, #e65b18 90%) !important;
    }


    .btn--success {
        background-color: #e65b1800 !important;
        background-image: radial-gradient(circle farthest-corner at 10% 20%, #96d832 0%, #00650f 90%) !important;
    }
    .btn--success:hover {
        background-color: #e65b1800 !important;
        background-image: radial-gradient(circle farthest-corner at 10% 20%, #00650f 0%, #96d832  90%) !important;
    }

    .btn-danger,
    .btn--danger,
    .bg-danger,
    .bg--danger {
        color: #fff;
        background-color: #dc3545;
        border-color: #dc3545;
        background-image: radial-gradient(circle farthest-corner at 10% 20%, #f4727f 0%, #dc3545 90%) !important;
    }

    .btn-danger:hover,
    .btn--danger:hover,
    .bg-danger:hover,
    .bg--danger:hover {
        color: #fff;
        background-color: #dc3545;
        border-color: #dc3545;
        background-image: radial-gradient(circle farthest-corner at 10% 20%, #dc3545 0%, #f4727f 90%) !important;
    }

    .border--primary {
        border-color: #e65b18 !important;
    }

    .btn-outline--primary {
        color: #e65b18;
        border-color: #e65b18;
        background-color: #e65b1800 !important;
    }

    .btn-outline--primary:hover {
        color: #ffffff;
        border-color: #e65b18;
        background-color: #e65b1800 !important;
        background-image: radial-gradient(circle farthest-corner at 10% 20%, #e24c00 0%, #ffb569 90%) !important;
    }

    .btn-outline--danger {
        color: #d00202;
        border-color: #d00202;
        background-color: #e65b1800 !important;
    }

    .btn-outline--danger:hover {
        color: #ffffff;
        border-color: #d00202;
        background-color: #e65b1800 !important;
        background-image: radial-gradient(circle farthest-corner at 10% 20%, #ec4a4a 0%, #d00202 90%) !important;
    }

    .sidebar__menu .sidebar-menu-item .side-menu--open,
    .sidebar__menu .sidebar-menu-item.active>a {
        background-color: #e65b1800 !important;
        background-image: radial-gradient(circle farthest-corner at 10% 20%, #e24c00 0%, #ffb569 90%) !important;
    }

    .sidebar__menu .sidebar-menu-item .side-menu--open:hover,
    .sidebar__menu .sidebar-menu-item>a:hover {
        background-color: rgb(127 127 127) !important;
    }

    .sidebar .slimScrollDiv .slimScrollBar {
        background-color: rgb(127 127 127) !important;
        width: 5px !important;
        opacity: 1 !important;
    }

    .sidebar[class*="bg--"] .sidebar__menu .sidebar__menu-header {
        color: #ffffff;
    }

    table.table--light thead th {
        background-color: #e65b1800;
    }

    table.table--light thead {
        background-color: #e65b1800;
        background-image: radial-gradient(circle farthest-corner at 10% 20%, #e24c00 0%, #ffb569 90%) !important;
    }

    .file-upload-wrapper:before {
        background: #4534ff00;
        background-image: radial-gradient(circle farthest-corner at 10% 20%, #e24c00 0%, #ffb569 90%) !important;
    }

    .card {
        border-radius: 20px;
        -webkit-border-radius: 20px;
    }

    .card-header:first-child {
        border-radius: calc(1.25rem - 1px) calc(.25rem - 1px) 0 0;
    }

    .box--shadow2 {
        box-shadow: 2px 1px 5px 2px #be88054f !important;
    }

    .widget-two {
        padding: 18px;
        border-radius: 20px !important;
        -webkit-border-radius: 20px !important;
    }

    .widget-two__btn {
        top: unset;
        right: 10px;
        border-radius: 10px;
        font-size: 12px;
    }

    .pagination .page-item.active .page-link {
        background-color: #4534ff00;
        border-color: #e65b18;
        background-image: radial-gradient(circle farthest-corner at 10% 20%, #e24c00 0%, #ffb569 90%) !important;
        color: #ffffff;
        box-shadow: 0 4px 10px 0 rgba(0, 0, 0, 0.25);
    }

    .form-control:focus,
    .form-control:active,
    .form-control:visited,
    .form-control:focus-within,
    input:focus,
    input:active,
    input:visited,
    input:focus-within,
    textarea:focus,
    textarea:active,
    textarea:visited,
    textarea:focus-within,
    select:focus,
    select:active,
    select:visited,
    select:focus-within {
        border-color: #05af4f;
        box-shadow: 0 3px 9px rgba(2, 185, 85, 0.47), 3px 4px 8px rgba(115, 103, 240, 0.1);
    }

    .sidebar[class*="bg--"] .sidebar__menu .sidebar-menu-item .menu-icon,
    .sidebar[class*="bg--"] .sidebar__menu .sidebar-menu-item .menu-title {
        color: #ffffff;
    }
    .select2-container--default .select2-selection--multiple {
        border-color: #ced4da !important;
        background-color: #fff !important;
        min-height: 40px !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #e65b18 !important;
        border: 1px solid #e65b18 !important;
        color: #fff !important;
    }
    .select2-container {
        z-index: 1040 !important;
    }
    .modal {
        z-index: 1050 !important;
    }
    .select2-dropdown {
        z-index: 1060 !important;
    }
    .select2-container {
        width: 100% !important;
    }
    .select2-search__field {
        width: 100% !important;
    }
    .modal-backdrop.fade.show {
        display: none !important;
    }
</style>
