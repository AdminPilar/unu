
//
//  Video killed the radio star 
//  *Fork : jcesar
//
   
var tag = document.createElement('script');
    tag.src = 'https://www.youtube.com/player_api';
var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
var tv,
   playerDefaults = {autoplay: 1, loop:1, autohide: 0, modestbranding: 1, rel: 0, fs: 0, showinfo: 0, controls: 0, disablekb: 1, enablejsapi: 0, iv_load_policy: 3};
var vid = [
      {'videoId': 'rjhOegQL2a8', 'startSeconds':1, 'endSeconds': 30, 'suggestedQuality': '240'}   
    ],
    randomVid = Math.floor(Math.random() * vid.length),
    currVid = randomVid;

$('.hi em:last-of-type').html(vid.length);



function onYouTubePlayerAPIReady(){
  tv = new YT.Player('tv', {events: {'onReady': onPlayerReady, 'onStateChange': onPlayerStateChange}, playerVars: playerDefaults});
}

function onPlayerReady(){
  tv.loadVideoById(vid[currVid]);
  tv.mute();
}

function onPlayerStateChange(e) {
  if (e.data === 1){
    $('#tv').addClass('active');
    $('.hi em:nth-of-type(2)').html(currVid + 1);
  } else if (e.data === 2){
    $('#tv').removeClass('active');
    if(currVid === vid.length - 1){
      currVid = 0;
    } else {
      currVid++;  
    }
    tv.loadVideoById(vid[currVid]);
    tv.seekTo(vid[currVid].startSeconds);
  }
}

function fvr(){

  var w = $("#video-cabeza").width(),
    h = $("#video-cabeza").height();

  if (w/h > 16/9){tv.setSize(w, w/16*9); $('.tv .screen').css({'left': '0px'}); } else {  tv.setSize(h/9*16, h);
    $('.tv .screen').css({'left': -($('.tv .screen').outerWidth()-w)/2});
  }
}









$(window).on('load resize', function(){ fvr();});


$('.hi span:first-of-type').on('click', function(){  $('#tv').toggleClass('mute');  $('.hi em:first-of-type').toggleClass('hidden');  if($('#tv').hasClass('mute')){
    tv.mute(); } else {  tv.unMute(); }});

$('.hi span:last-of-type').on('click', function(){ $('.hi em:nth-of-type(2)').html('~'); tv.pauseVideo();});


