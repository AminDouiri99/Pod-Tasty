$(document).ready(function()
{"use strict";var ctrl=new ScrollMagic.Controller();setHeader();initMenu();initMilestones();initGallery();$(window).on('resize',function()
{setHeader();setTimeout(function()
{$(window).trigger('resize.px.parallax');},375);});$(document).on('scroll',function()
{setHeader();});function setHeader()
{var header=$('.header');if($(window).scrollTop()>91)
{header.addClass('scrolled');}
else
{header.removeClass('scrolled');}}
function initMenu()
{if($('.menu').length)
{var menu=$('.menu');var hamburger=$('.hamburger');var close=$('.menu_close');hamburger.on('click',function()
{menu.toggleClass('active');});close.on('click',function()
{menu.toggleClass('active');});}}
function initMilestones()
{if($('.milestone_counter').length)
{var milestoneItems=$('.milestone_counter');milestoneItems.each(function(i)
{var ele=$(this);var endValue=ele.data('end-value');var eleValue=ele.text();var signBefore="";var signAfter="";if(ele.attr('data-sign-before'))
{signBefore=ele.attr('data-sign-before');}
if(ele.attr('data-sign-after'))
{signAfter=ele.attr('data-sign-after');}
var milestoneScene=new ScrollMagic.Scene({triggerElement:this,triggerHook:'onEnter',reverse:false}).on('start',function()
{var counter={value:eleValue};var counterTween=TweenMax.to(counter,4,{value:endValue,roundProps:"value",ease:Circ.easeOut,onUpdate:function()
{document.getElementsByClassName('milestone_counter')[i].innerHTML=signBefore+counter.value+signAfter;}});}).addTo(ctrl);});}}
function initGallery()
{if($('.gallery_item').length)
{$('.colorbox').colorbox({rel:'colorbox',photo:true,maxWidth:'95%',maxHeight:'95%'});}}});