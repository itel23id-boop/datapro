<!doctype html>
<title>{{ !$config ? '' : $config->judul_web }} - Error</title>
<link rel="shortcut icon" href="{{ url('') }}{{ !$config ? '' : $config->logo_favicon }}">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
<style>
  html, body { padding: 0; margin: 0; width: 100%; height: 100%; }
  * {box-sizing: border-box;}
  body { text-align: center; padding: 0; background: #000000; color: #fff; font-family: Open Sans; }
  h1 { font-size: 50px; font-weight: 100; text-align: center;}
  body { font-family: Open Sans; font-weight: 100; font-size: 20px; color: #fff; text-align: center; display: -webkit-box; display: -ms-flexbox; display: flex; -webkit-box-pack: center; -ms-flex-pack: center; justify-content: center; -webkit-box-align: center; -ms-flex-align: center; align-items: center;}
  article { display: block; width: 700px; padding: 50px; margin: 0 auto; }
  a { color: #fff; font-weight: bold;}
  a:hover { text-decoration: none; }
  svg { width: 75px; margin-top: 1em; }
        .fasttopup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }
        .fasttopup li{
            position: absolute;
            
            display: block;
            list-style: none;
            width: 20px;
            height: 20px;
            background: rgba(202, 202, 203, 0.2);
         
            animation: animate 25s linear infinite;
            bottom: -150px;
            
        }
        
        .fasttopup li:nth-child(1){
            left: 25%;
            width: 80px;
            height: 80px;
            animation-delay: 0s;
            background-image :linear-gradient(45deg, #000000, #FEC832);
        }
        
        
        .fasttopup li:nth-child(2){
            left: 10%;
            width: 20px;
            height: 20px;
            animation-delay: 2s;
            animation-duration: 12s;
            background-image : linear-gradient(45deg, #000000, #FEC832);
        }
        
        .fasttopup li:nth-child(3){
            left: 70%;
            width: 20px;
            height: 20px;
            animation-delay: 4s;
            background-image : linear-gradient(45deg, #cdcbcb, #258f0a);
        }
        
        .fasttopup li:nth-child(4){
            left: 40%;
            width: 60px;
            height: 60px;
            animation-delay: 0s;
            animation-duration: 18s;
            background-image : linear-gradient(45deg, #ffffff, #1CB0F6);
        }
        
        .fasttopup li:nth-child(5){
            left: 65%;
            width: 20px;
            height: 20px;
            animation-delay: 0s;
            background-image : linear-gradient(45deg, #000000, #FA811B);
        }
        
        .fasttopup li:nth-child(6){
            left: 75%;
            width: 110px;
            height: 110px;
            animation-delay: 3s;
            background-image : linear-gradient(45deg, var(--warna), var(--danger));
        }
        
        .fasttopup li:nth-child(7){
            left: 35%;
            width: 150px;
            height: 150px;
            animation-delay: 7s;
            background-image : linear-gradient(45deg, var(--warna_1), var(--success));
        }
        
        .fasttopup li:nth-child(8){
            left: 50%;
            width: 25px;
            height: 25px;
            animation-delay: 15s;
            animation-duration: 45s;
            background-image : linear-gradient(45deg, var(--warna_2), var(--info));
        }
        
        .fasttopup li:nth-child(9){
            left: 20%;
            width: 15px;
            height: 15px;
            animation-delay: 2s;
            animation-duration: 35s;
            background-image : linear-gradient(45deg, var(--warna_3), var(--danger));
        }
        
        .fasttopup li:nth-child(10){
            left: 85%;
            width: 150px;
            height: 150px;
            animation-delay: 0s;
            animation-duration: 11s;
            background-image : linear-gradient(45deg, var(--warna_4), var(--warning));
        }
        @keyframes  animate {
            0%{
                transform: translateY(0) rotate(0deg);
                opacity: 1;
                border-radius: 0;
            }
            100%{
                transform: translateY(-1000px) rotate(720deg);
                opacity: 0;
                border-radius: 50%;
            }
        }
        .animate-shimmer {
            animation: shimmer 1.75s linear infinite
        }
        .fab-container {
            position: fixed;
            bottom: 70px;
            right: 10px;
            z-index: 999;
            cursor: pointer;
        }
    
        .fab-icon-holder {
            width: 45px;
            height: 45px;
            bottom: 140px;
            left: 10px;
            color: #FFF;
            background: #FFF;
            /* padding: 1px; */
            border-radius: 10px;
            text-align: center;
            font-size: 30px;
            z-index: 99999;
        }
    
        .fab-icon-holder:hover {
            opacity: 0.8;
        }
    
        .fab-icon-holder i {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            font-size: 25px;
            color: #ffffff;
        }
    
        .fab-options {
            list-style-type: none;
            margin: 0;
            position: absolute;
            bottom: 48px;
            left: -37px;
            opacity: 0;
            transition: all 0.3s ease;
            transform: scale(0);
            transform-origin: 85% bottom;
        }
    
        .fab:hover+.fab-options,
        .fab-options:hover {
            opacity: 1;
            transform: scale(1);
        }
    
        .fab-options li {
            display: flex;
            justify-content: flex-start;
            padding: 5px;
        }
    
        .fab-label {
            padding: 2px 5px;
            align-self: center;
            user-select: none;
            white-space: nowrap;
            border-radius: 3px;
            font-size: 16px;
            background: #666666;
            color: #ffffff;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            margin-left: 10px;
        }
        .img-chat {
            max-width: 100%;
            height: auto;
            /* background-color: #f89728; */
            border-radius: 10px;
        }
        .btn {
            font-family: 'Inter', sans-serif;
            letter-spacing: 0;
            font-weight: 500;
            padding: 0.719rem 1rem;
            font-size: 14px;
            line-height: 20px;
            border-radius: 8px;
        }
        .btn-primary {
            color: #fff;
            background-color: #1b00ff;
            border-color: #1b00ff;
        }
</style>
<div class="fasttopup">
	   <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
</div>
<article>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 202.24 202.24"><defs><style>.cls-1{fill:#fff;}</style></defs><title>Asset 3</title><g id="Layer_2" data-name="Layer 2"><g id="Capa_1" data-name="Capa 1"><path class="cls-1" d="M101.12,0A101.12,101.12,0,1,0,202.24,101.12,101.12,101.12,0,0,0,101.12,0ZM159,148.76H43.28a11.57,11.57,0,0,1-10-17.34L91.09,31.16a11.57,11.57,0,0,1,20.06,0L169,131.43a11.57,11.57,0,0,1-10,17.34Z"/><path class="cls-1" d="M101.12,36.93h0L43.27,137.21H159L101.13,36.94Zm0,88.7a7.71,7.71,0,1,1,7.71-7.71A7.71,7.71,0,0,1,101.12,125.63Zm7.71-50.13a7.56,7.56,0,0,1-.11,1.3l-3.8,22.49a3.86,3.86,0,0,1-7.61,0l-3.8-22.49a8,8,0,0,1-.11-1.3,7.71,7.71,0,1,1,15.43,0Z"/></g></g></svg>
    <h1>404 Server Error</h1>
    <div>
        <p>Oops. Server Error.</p>
        <p>You may have mistyped the address or the page may have moved.</p>
        <p><a href="{{ url('/') }}" class="btn btn-primary">Back to Home</a></p>
    </div>
</article>
<div class="fab-container">
    <div class="fab fab-icon-holder">
    <img src="/assets/callcenter.png" class="img-chat" alt="">
    </div>
    <ul class="fab-options">
    <li>
    <a href="{{ !$config ? '' : $config->url_ig }}" class="text-decoration-none" target="_blank">
    <div class="fab-icon-holder" style="background: radial-gradient(circle farthest-corner at 35% 90%, #fec564, transparent 50%), radial-gradient(circle farthest-corner at 0 140%, #fec564, transparent 50%), radial-gradient(ellipse farthest-corner at 0 -25%, #5258cf, transparent 50%), radial-gradient(ellipse farthest-corner at 20% -50%, #5258cf, transparent 50%), radial-gradient(ellipse farthest-corner at 100% 0, #893dc2, transparent 50%), radial-gradient(ellipse farthest-corner at 60% -20%, #893dc2, transparent 50%), radial-gradient(ellipse farthest-corner at 100% 100%, #d9317a, transparent), linear-gradient(#6559ca, #bc318f 30%, #e33f5f 50%, #f77638 70%, #fec66d 100%);">
    <i class="fab fa-instagram"></i>
    </div>
    </a>
    </li>
    <li>
    <a href="{{ !$config ? '' : $config->url_wa }}" class="text-decoration-none" target="_blank">
    <div class="fab-icon-holder" style="background-color: #25D366;">
    <i class="fab fa-whatsapp"></i>
    </div>
    </a>
    </li>
    <li>
    </li>
</div>