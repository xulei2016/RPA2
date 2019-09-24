@extends('admin.layouts.wrapper-content') @section('content')

<style>
    /* FROM HTTP://WWW.GETBOOTSTRAP.COM
        * Glyphicons
        *
        * Special styles for displaying the icons and their classes in the docs.
        */

    .bs-glyphicons {
        padding-left: 0;
        padding-bottom: 1px;
        margin-bottom: 20px;
        list-style: none;
        overflow: hidden;
    }

    .bs-glyphicons li {
        float: left;
        width: 25%;
        height: 115px;
        padding: 10px;
        margin: 0 -1px -1px 0;
        font-size: 12px;
        line-height: 1.4;
        text-align: center;
        border: 1px solid #ddd;
    }

    .bs-glyphicons .glyphicon {
        margin-top: 5px;
        margin-bottom: 10px;
        font-size: 24px;
    }

    .bs-glyphicons .glyphicon-class {
        display: block;
        text-align: center;
        word-wrap: break-word;
        /* Help out IE10+ with class names */
    }

    .bs-glyphicons li:hover {
        background-color: rgba(86, 61, 124, .1);
    }

    @media (min-width: 768px) {
        .bs-glyphicons li {
            width: 12.5%;
        }
    }
</style>

<!-- Main content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <div class="card card-primary card-outline">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="#fa-icons" data-toggle="tab">Font Awesome</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#glyphicons" data-toggle="tab">Glyphicons</a>
                        </li>
                    </ul>
                    <div class="card-body tab-content">
                        <!-- Font Awesome Icons -->
                        <div class="tab-pane active" id="fa-icons">


                            <section id="new">
                                <h2 class="page-header">最新的4.7版，新增了41个图标</h2>
                                <div class="row fontawesome-icon-list">
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-address-book" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>address-book
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-address-book-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>address-book-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-address-card" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>address-card
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-address-card-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>address-card-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bandcamp" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bandcamp
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bath" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bath
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bathtub" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bathtub
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-drivers-license" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>drivers-license
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-drivers-license-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>drivers-license-o
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-eercast" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>eercast
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-envelope-open" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>envelope-open
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-envelope-open-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>envelope-open-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-etsy" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>etsy
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-free-code-camp" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>free-code-camp
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-grav" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>grav
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-handshake-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>handshake-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-id-badge" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>id-badge
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-id-card" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>id-card
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-id-card-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>id-card-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-imdb" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>imdb
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-linode" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>linode
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-meetup" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>meetup
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-microchip" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>microchip
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-podcast" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>podcast
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-quora" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>quora
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-ravelry" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>ravelry
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-s15" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>s15
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-shower" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>shower
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-snowflake-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>snowflake-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-superpowers" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>superpowers
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-telegram" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>telegram
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thermometer" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thermometer
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thermometer-0" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thermometer-0
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thermometer-1" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thermometer-1
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thermometer-2" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thermometer-2
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thermometer-3" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thermometer-3
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thermometer-4" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thermometer-4
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thermometer-empty" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thermometer-empty
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thermometer-full" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thermometer-full
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thermometer-half" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thermometer-half
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thermometer-quarter" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thermometer-quarter
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thermometer-three-quarters" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thermometer-three-quarters
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-times-rectangle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>times-rectangle
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-times-rectangle-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>times-rectangle-o
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-user-circle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>user-circle
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>user-circle-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-user-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>user-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-vcard" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>vcard
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-vcard-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>vcard-o
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-window-close" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>window-close
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-window-close-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>window-close-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-window-maximize" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>window-maximize
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-window-minimize" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>window-minimize
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-window-restore" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>window-restore
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-wpexplorer" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>wpexplorer
                                    </div>
                                </div>
                            </section>
                            <section id="web-application">
                                <h2 class="page-header">Web Application Icons</h2>
                                <div class="row fontawesome-icon-list">
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-address-book" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>address-book
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-address-book-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>address-book-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-address-card" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>address-card
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-address-card-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>address-card-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-adjust" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>adjust
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-american-sign-language-interpreting" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>american-sign-language-interpreting
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-anchor" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>anchor
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-archive" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>archive
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-area-chart" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>area-chart
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-arrows" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>arrows
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-arrows-h" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>arrows-h
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-arrows-v" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>arrows-v
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-asl-interpreting" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>asl-interpreting
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-assistive-listening-systems" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>assistive-listening-systems
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-asterisk" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>asterisk
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-at" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>at
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-audio-description" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>audio-description
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-automobile" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>automobile
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-balance-scale" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>balance-scale
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-ban" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>ban
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bank" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bank
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bar-chart" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bar-chart
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bar-chart-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bar-chart-o
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-barcode" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>barcode
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bars" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bars
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bath" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bath
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bathtub" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bathtub
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-battery" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>battery
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-battery-0" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>battery-0
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-battery-1" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>battery-1
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-battery-2" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>battery-2
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-battery-3" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>battery-3
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-battery-4" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>battery-4
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-battery-empty" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>battery-empty
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-battery-full" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>battery-full
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-battery-half" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>battery-half
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-battery-quarter" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>battery-quarter
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-battery-three-quarters" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>battery-three-quarters
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bed" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bed
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-beer" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>beer
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bell" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bell
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bell-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bell-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bell-slash" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bell-slash
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bell-slash-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bell-slash-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bicycle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bicycle
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-binoculars" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>binoculars
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-birthday-cake" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>birthday-cake
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-blind" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>blind
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bluetooth" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bluetooth
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bluetooth-b" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bluetooth-b
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bolt" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bolt
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bomb" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bomb
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-book" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>book
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bookmark" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bookmark
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bookmark-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bookmark-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-braille" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>braille
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-briefcase" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>briefcase
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bug" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bug
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-building" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>building
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-building-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>building-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bullhorn" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bullhorn
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bullseye" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bullseye
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bus" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bus
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cab" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cab
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-calculator" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>calculator
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>calendar
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>calendar-check-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-calendar-minus-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>calendar-minus-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-calendar-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>calendar-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-calendar-plus-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>calendar-plus-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-calendar-times-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>calendar-times-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-camera" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>camera
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-camera-retro" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>camera-retro
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-car" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>car
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-caret-square-o-down" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>caret-square-o-down
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-caret-square-o-left" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>caret-square-o-left
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-caret-square-o-right" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>caret-square-o-right
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-caret-square-o-up" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>caret-square-o-up
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cart-arrow-down" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cart-arrow-down
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cart-plus" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cart-plus
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cc" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cc
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-certificate" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>certificate
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-check" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>check
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>check-circle
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-check-circle-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>check-circle-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-check-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>check-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>check-square-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-child" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>child
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-circle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>circle
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-circle-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>circle-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-circle-o-notch" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>circle-o-notch
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-circle-thin" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>circle-thin
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>clock-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-clone" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>clone
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-close" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>close
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cloud" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cloud
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cloud-download" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cloud-download
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cloud-upload
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-code" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>code
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-code-fork" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>code-fork
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-coffee" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>coffee
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cog" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cog
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cogs" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cogs
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-comment" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>comment
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-comment-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>comment-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-commenting" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>commenting
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-commenting-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>commenting-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-comments" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>comments
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-comments-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>comments-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-compass" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>compass
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-copyright" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>copyright
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-creative-commons" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>creative-commons
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-credit-card" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>credit-card
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-credit-card-alt" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>credit-card-alt
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-crop" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>crop
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-crosshairs" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>crosshairs
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cube" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cube
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cubes" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cubes
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cutlery" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cutlery
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-dashboard" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>dashboard
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-database" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>database
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-deaf" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>deaf
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-deafness" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>deafness
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-desktop" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>desktop
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-diamond" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>diamond
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>dot-circle-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-download" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>download
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-drivers-license" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>drivers-license
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-drivers-license-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>drivers-license-o
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>edit
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>ellipsis-h
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>ellipsis-v
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>envelope
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>envelope-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-envelope-open" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>envelope-open
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-envelope-open-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>envelope-open-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-envelope-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>envelope-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-eraser" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>eraser
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-exchange" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>exchange
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-exclamation" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>exclamation
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>exclamation-circle
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>exclamation-triangle
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-external-link" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>external-link
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-external-link-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>external-link-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>eye
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>eye-slash
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-eyedropper" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>eyedropper
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-fax" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>fax
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-feed" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>feed
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-female" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>female
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-fighter-jet" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>fighter-jet
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-archive-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-archive-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-audio-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-audio-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-code-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-code-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-excel-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-image-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-image-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-movie-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-movie-o
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-pdf-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-photo-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-photo-o
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-picture-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-picture-o
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-powerpoint-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-powerpoint-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-sound-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-sound-o
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-video-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-video-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-word-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-word-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-zip-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-zip-o
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-film" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>film
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-filter" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>filter
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-fire" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>fire
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-fire-extinguisher" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>fire-extinguisher
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-flag" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>flag
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-flag-checkered" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>flag-checkered
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-flag-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>flag-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-flash" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>flash
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-flask" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>flask
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-folder" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>folder
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-folder-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>folder-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-folder-open" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>folder-open
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-folder-open-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>folder-open-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-frown-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>frown-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-futbol-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>futbol-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-gamepad" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>gamepad
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-gavel" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>gavel
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-gear" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>gear
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-gears" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>gears
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-gift" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>gift
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-glass" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>glass
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-globe" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>globe
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>graduation-cap
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-group" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>group
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hand-grab-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hand-grab-o
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hand-lizard-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hand-lizard-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hand-paper-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hand-paper-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hand-peace-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hand-peace-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hand-pointer-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hand-pointer-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hand-rock-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hand-rock-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hand-scissors-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hand-scissors-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hand-spock-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hand-spock-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hand-stop-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hand-stop-o
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-handshake-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>handshake-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hard-of-hearing" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hard-of-hearing
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hashtag" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hashtag
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hdd-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hdd-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-headphones" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>headphones
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-heart" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>heart
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-heart-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>heart-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-heartbeat" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>heartbeat
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-history" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>history
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-home" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>home
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hotel" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hotel
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hourglass" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hourglass
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hourglass-1" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hourglass-1
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hourglass-2" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hourglass-2
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hourglass-3" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hourglass-3
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hourglass-end" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hourglass-end
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hourglass-half" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hourglass-half
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hourglass-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hourglass-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hourglass-start" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hourglass-start
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-i-cursor" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>i-cursor
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-id-badge" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>id-badge
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-id-card" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>id-card
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-id-card-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>id-card-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-image" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>image
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-inbox" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>inbox
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-industry" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>industry
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-info" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>info
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>info-circle
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-institution" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>institution
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-key" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>key
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-keyboard-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>keyboard-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-language" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>language
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-laptop" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>laptop
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-leaf" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>leaf
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-legal" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>legal
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-lemon-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>lemon-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-level-down" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>level-down
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-level-up" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>level-up
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-life-bouy" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>life-bouy
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-life-buoy" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>life-buoy
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-life-ring" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>life-ring
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-life-saver" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>life-saver
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-lightbulb-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>lightbulb-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-line-chart" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>line-chart
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-location-arrow" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>location-arrow
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-lock" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>lock
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-low-vision" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>low-vision
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-magic" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>magic
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-magnet" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>magnet
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-mail-forward" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>mail-forward
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-mail-reply" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>mail-reply
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-mail-reply-all" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>mail-reply-all
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-male" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>male
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-map" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>map
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>map-marker
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-map-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>map-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-map-pin" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>map-pin
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-map-signs" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>map-signs
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-meh-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>meh-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-microchip" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>microchip
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-microphone" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>microphone
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-microphone-slash" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>microphone-slash
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>minus
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-minus-circle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>minus-circle
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-minus-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>minus-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-minus-square-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>minus-square-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-mobile" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>mobile
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-mobile-phone" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>mobile-phone
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-money" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>money
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-moon-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>moon-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-mortar-board" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>mortar-board
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-motorcycle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>motorcycle
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-mouse-pointer" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>mouse-pointer
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-music" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>music
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-navicon" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>navicon
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-newspaper-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>newspaper-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-object-group" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>object-group
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-object-ungroup" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>object-ungroup
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-paint-brush" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>paint-brush
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>paper-plane
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-paper-plane-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>paper-plane-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-paw" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>paw
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>pencil
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-pencil-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>pencil-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>pencil-square-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-percent" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>percent
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-phone" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>phone
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-phone-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>phone-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-photo" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>photo
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-picture-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>picture-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-pie-chart" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>pie-chart
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-plane" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>plane
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-plug" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>plug
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>plus
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>plus-circle
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-plus-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>plus-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-plus-square-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>plus-square-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-podcast" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>podcast
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-power-off" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>power-off
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-print" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>print
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-puzzle-piece" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>puzzle-piece
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-qrcode" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>qrcode
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-question" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>question
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-question-circle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>question-circle
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-question-circle-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>question-circle-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-quote-left" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>quote-left
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-quote-right" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>quote-right
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-random" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>random
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-recycle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>recycle
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-refresh" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>refresh
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-registered" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>registered
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-remove" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>remove
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-reorder" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>reorder
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-reply" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>reply
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-reply-all" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>reply-all
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-retweet" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>retweet
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-road" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>road
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-rocket" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>rocket
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-rss" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>rss
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-rss-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>rss-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-s15" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>s15
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-search" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>search
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-search-minus" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>search-minus
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-search-plus" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>search-plus
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-send" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>send
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-send-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>send-o
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-server" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>server
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-share" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>share
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-share-alt" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>share-alt
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-share-alt-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>share-alt-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-share-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>share-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-share-square-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>share-square-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-shield" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>shield
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-ship" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>ship
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>shopping-bag
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>shopping-basket
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>shopping-cart
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-shower" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>shower
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-sign-in" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>sign-in
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-sign-language" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>sign-language
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-sign-out" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>sign-out
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-signal" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>signal
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-signing" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>signing
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-sitemap" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>sitemap
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-sliders" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>sliders
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-smile-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>smile-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-snowflake-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>snowflake-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-soccer-ball-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>soccer-ball-o
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-sort" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>sort
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-sort-alpha-asc" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>sort-alpha-asc
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-sort-alpha-desc" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>sort-alpha-desc
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-sort-amount-asc" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>sort-amount-asc
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-sort-amount-desc" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>sort-amount-desc
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-sort-asc" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>sort-asc
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-sort-desc" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>sort-desc
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-sort-down" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>sort-down
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-sort-numeric-asc" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>sort-numeric-asc
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-sort-numeric-desc" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>sort-numeric-desc
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-sort-up" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>sort-up
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-space-shuttle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>space-shuttle
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-spinner" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>spinner
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-spoon" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>spoon
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-square-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>square-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>star
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-star-half" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>star-half
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-star-half-empty" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>star-half-empty
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-star-half-full" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>star-half-full
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-star-half-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>star-half-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-star-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>star-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-sticky-note" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>sticky-note
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-sticky-note-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>sticky-note-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-street-view" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>street-view
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-suitcase" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>suitcase
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-sun-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>sun-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-support" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>support
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-tablet" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>tablet
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-tachometer" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>tachometer
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-tag" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>tag
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-tags" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>tags
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-tasks" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>tasks
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-taxi" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>taxi
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-television" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>television
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-terminal" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>terminal
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thermometer" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thermometer
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thermometer-0" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thermometer-0
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thermometer-1" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thermometer-1
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thermometer-2" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thermometer-2
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thermometer-3" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thermometer-3
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thermometer-4" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thermometer-4
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thermometer-empty" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thermometer-empty
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thermometer-full" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thermometer-full
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thermometer-half" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thermometer-half
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thermometer-quarter" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thermometer-quarter
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thermometer-three-quarters" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thermometer-three-quarters
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thumb-tack" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thumb-tack
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thumbs-down" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thumbs-down
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thumbs-o-down" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thumbs-o-down
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thumbs-o-up
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thumbs-up
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-ticket" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>ticket
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-times" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>times
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-times-circle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>times-circle
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-times-circle-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>times-circle-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-times-rectangle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>times-rectangle
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-times-rectangle-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>times-rectangle-o
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-tint" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>tint
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-toggle-down" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>toggle-down
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-toggle-left" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>toggle-left
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-toggle-off" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>toggle-off
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-toggle-on" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>toggle-on
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-toggle-right" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>toggle-right
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-toggle-up" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>toggle-up
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-trademark" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>trademark
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>trash
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>trash-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-tree" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>tree
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-trophy" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>trophy
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-truck" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>truck
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-tty" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>tty
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-tv" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>tv
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-umbrella" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>umbrella
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-universal-access" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>universal-access
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-university" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>university
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-unlock" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>unlock
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>unlock-alt
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-unsorted" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>unsorted
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-upload" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>upload
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-user" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>user
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-user-circle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>user-circle
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>user-circle-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-user-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>user-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>user-plus
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-user-secret" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>user-secret
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-user-times" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>user-times
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-users" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>users
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-vcard" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>vcard
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-vcard-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>vcard-o
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-video-camera" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>video-camera
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-volume-control-phone" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>volume-control-phone
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-volume-down" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>volume-down
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-volume-off" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>volume-off
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-volume-up" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>volume-up
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-warning" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>warning
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-wheelchair" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>wheelchair
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-wheelchair-alt" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>wheelchair-alt
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-wifi" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>wifi
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-window-close" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>window-close
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-window-close-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>window-close-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-window-maximize" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>window-maximize
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-window-minimize" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>window-minimize
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-window-restore" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>window-restore
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-wrench" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>wrench
                                    </div>
                                </div>
                            </section>
                            <section id="accessibility">
                                <h2 class="page-header">Accessibility Icons</h2>
                                <div class="row fontawesome-icon-list">
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-american-sign-language-interpreting" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>american-sign-language-interpreting
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-asl-interpreting" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>asl-interpreting
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-assistive-listening-systems" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>assistive-listening-systems
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-audio-description" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>audio-description
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-blind" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>blind
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-braille" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>braille
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cc" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cc
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-deaf" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>deaf
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-deafness" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>deafness
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hard-of-hearing" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hard-of-hearing
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-low-vision" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>low-vision
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-question-circle-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>question-circle-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-sign-language" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>sign-language
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-signing" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>signing
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-tty" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>tty
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-universal-access" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>universal-access
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-volume-control-phone" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>volume-control-phone
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-wheelchair" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>wheelchair
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-wheelchair-alt" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>wheelchair-alt
                                    </div>
                                </div>
                            </section>
                            <section id="hand">
                                <h2 class="page-header">Hand Icons</h2>
                                <div class="row fontawesome-icon-list">
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hand-grab-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hand-grab-o
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hand-lizard-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hand-lizard-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hand-o-down" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hand-o-down
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hand-o-left" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hand-o-left
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hand-o-right
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hand-o-up" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hand-o-up
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hand-paper-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hand-paper-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hand-peace-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hand-peace-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hand-pointer-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hand-pointer-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hand-rock-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hand-rock-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hand-scissors-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hand-scissors-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hand-spock-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hand-spock-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hand-stop-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hand-stop-o
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thumbs-down" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thumbs-down
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thumbs-o-down" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thumbs-o-down
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thumbs-o-up
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>thumbs-up
                                    </div>
                                </div>
                            </section>
                            <section id="transportation">
                                <h2 class="page-header">Transportation Icons</h2>
                                <div class="row fontawesome-icon-list">
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-ambulance" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>ambulance
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-automobile" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>automobile
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bicycle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bicycle
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bus" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bus
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cab" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cab
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-car" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>car
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-fighter-jet" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>fighter-jet
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-motorcycle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>motorcycle
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-plane" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>plane
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-rocket" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>rocket
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-ship" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>ship
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-space-shuttle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>space-shuttle
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-subway" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>subway
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-taxi" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>taxi
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-train" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>train
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-truck" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>truck
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-wheelchair" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>wheelchair
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-wheelchair-alt" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>wheelchair-alt
                                    </div>
                                </div>
                            </section>
                            <section id="gender">
                                <h2 class="page-header">Gender Icons</h2>
                                <div class="row fontawesome-icon-list">
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-genderless" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>genderless
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-intersex" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>intersex
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-mars" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>mars
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-mars-double" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>mars-double
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-mars-stroke" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>mars-stroke
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-mars-stroke-h" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>mars-stroke-h
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-mars-stroke-v" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>mars-stroke-v
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-mercury" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>mercury
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-neuter" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>neuter
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-transgender" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>transgender
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-transgender-alt" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>transgender-alt
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-venus" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>venus
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-venus-double" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>venus-double
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-venus-mars" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>venus-mars
                                    </div>
                                </div>
                            </section>
                            <section id="file-type">
                                <h2 class="page-header">File Type Icons</h2>
                                <div class="row fontawesome-icon-list">
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-archive-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-archive-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-audio-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-audio-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-code-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-code-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-excel-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-image-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-image-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-movie-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-movie-o
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-pdf-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-photo-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-photo-o
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-picture-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-picture-o
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-powerpoint-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-powerpoint-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-sound-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-sound-o
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-text" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-text
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-text-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-video-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-video-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-word-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-word-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-zip-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-zip-o
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                </div>
                            </section>
                            <section id="spinner">
                                <h2 class="page-header">Spinner Icons</h2>

                                <div class="row fontawesome-icon-list">
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-circle-o-notch" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>circle-o-notch
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cog" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cog
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-gear" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>gear
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-refresh" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>refresh
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-spinner" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>spinner
                                    </div>
                                </div>
                            </section>
                            <section id="form-control">
                                <h2 class="page-header">Form Control Icons</h2>
                                <div class="row fontawesome-icon-list">
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-check-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>check-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>check-square-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-circle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>circle
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-circle-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>circle-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>dot-circle-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-minus-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>minus-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-minus-square-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>minus-square-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-plus-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>plus-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-plus-square-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>plus-square-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-square-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>square-o
                                    </div>
                                </div>
                            </section>
                            <section id="payment">
                                <h2 class="page-header">Payment Icons</h2>
                                <div class="row fontawesome-icon-list">
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cc-amex" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cc-amex
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cc-diners-club" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cc-diners-club
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cc-discover" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cc-discover
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cc-jcb" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cc-jcb
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cc-mastercard" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cc-mastercard
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cc-paypal" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cc-paypal
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cc-stripe" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cc-stripe
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cc-visa" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cc-visa
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-credit-card" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>credit-card
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-credit-card-alt" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>credit-card-alt
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-google-wallet" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>google-wallet
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-paypal" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>paypal
                                    </div>
                                </div>
                            </section>
                            <section id="chart">
                                <h2 class="page-header">Chart Icons</h2>
                                <div class="row fontawesome-icon-list">
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-area-chart" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>area-chart
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bar-chart" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bar-chart
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bar-chart-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bar-chart-o
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-line-chart" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>line-chart
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-pie-chart" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>pie-chart
                                    </div>
                                </div>
                            </section>
                            <section id="currency">
                                <h2 class="page-header">Currency Icons</h2>
                                <div class="row fontawesome-icon-list">
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bitcoin" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bitcoin
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-btc" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>btc
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cny" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cny
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-dollar" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>dollar
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-eur" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>eur
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-euro" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>euro
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-gbp" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>gbp
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-gg" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>gg
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-gg-circle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>gg-circle
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-ils" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>ils
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-inr" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>inr
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-jpy" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>jpy
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-krw" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>krw
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-money" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>money
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-rmb" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>rmb
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-rouble" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>rouble
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-rub" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>rub
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-ruble" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>ruble
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-rupee" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>rupee
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-shekel" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>shekel
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-sheqel" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>sheqel
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-try" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>try
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-turkish-lira" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>turkish-lira
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-usd" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>usd
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-won" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>won
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-yen" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>yen
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                </div>
                            </section>
                            <section id="text-editor">
                                <h2 class="page-header">Text Editor Icons</h2>
                                <div class="row fontawesome-icon-list">
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-align-center" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>align-center
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-align-justify" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>align-justify
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-align-left" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>align-left
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-align-right" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>align-right
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bold" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bold
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-chain" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>chain
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-chain-broken" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>chain-broken
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-clipboard" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>clipboard
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-columns" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>columns
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-copy" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>copy
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cut" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cut
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-dedent" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>dedent
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-eraser" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>eraser
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-text" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-text
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>file-text-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-files-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>files-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>floppy-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-font" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>font
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-header" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>header
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-indent" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>indent
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-italic" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>italic
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-link" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>link
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-list" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>list
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-list-alt" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>list-alt
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-list-ol" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>list-ol
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-list-ul" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>list-ul
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-outdent" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>outdent
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-paperclip" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>paperclip
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-paragraph" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>paragraph
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-paste" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>paste
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-repeat" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>repeat
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-rotate-left" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>rotate-left
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-rotate-right" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>rotate-right
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-save" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>save
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-scissors" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>scissors
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-strikethrough" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>strikethrough
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-subscript" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>subscript
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-superscript" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>superscript
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-table" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>table
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-text-height" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>text-height
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-text-width" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>text-width
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-th" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>th
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-th-large" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>th-large
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-th-list" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>th-list
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-underline" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>underline
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-undo" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>undo
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-unlink" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>unlink
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                </div>
                            </section>
                            <section id="directional">
                                <h2 class="page-header">Directional Icons</h2>
                                <div class="row fontawesome-icon-list">
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-angle-double-down" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>angle-double-down
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-angle-double-left" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>angle-double-left
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>angle-double-right
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-angle-double-up" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>angle-double-up
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>angle-down
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-angle-left" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>angle-left
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>angle-right
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-angle-up" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>angle-up
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-arrow-circle-down" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>arrow-circle-down
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>arrow-circle-left
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>arrow-circle-o-down
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>arrow-circle-o-left
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>arrow-circle-o-right
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>arrow-circle-o-up
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>arrow-circle-right
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-arrow-circle-up" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>arrow-circle-up
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-arrow-down" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>arrow-down
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>arrow-left
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>arrow-right
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-arrow-up" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>arrow-up
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-arrows" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>arrows
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-arrows-alt" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>arrows-alt
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-arrows-h" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>arrows-h
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-arrows-v" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>arrows-v
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-caret-down" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>caret-down
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-caret-left" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>caret-left
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-caret-right" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>caret-right
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-caret-square-o-down" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>caret-square-o-down
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-caret-square-o-left" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>caret-square-o-left
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-caret-square-o-right" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>caret-square-o-right
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-caret-square-o-up" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>caret-square-o-up
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-caret-up" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>caret-up
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-chevron-circle-down" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>chevron-circle-down
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-chevron-circle-left" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>chevron-circle-left
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-chevron-circle-right" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>chevron-circle-right
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-chevron-circle-up" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>chevron-circle-up
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>chevron-down
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>chevron-left
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>chevron-right
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-chevron-up" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>chevron-up
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-exchange" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>exchange
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hand-o-down" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hand-o-down
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hand-o-left" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hand-o-left
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hand-o-right
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hand-o-up" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hand-o-up
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-long-arrow-down" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>long-arrow-down
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-long-arrow-left" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>long-arrow-left
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>long-arrow-right
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-long-arrow-up" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>long-arrow-up
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-toggle-down" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>toggle-down
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-toggle-left" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>toggle-left
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-toggle-right" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>toggle-right
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-toggle-up" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>toggle-up
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                </div>
                            </section>
                            <section id="video-player">
                                <h2 class="page-header">Video Player Icons</h2>
                                <div class="row fontawesome-icon-list">
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-arrows-alt" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>arrows-alt
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-backward" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>backward
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-compress" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>compress
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-eject" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>eject
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-expand" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>expand
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-fast-backward" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>fast-backward
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-fast-forward" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>fast-forward
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-forward" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>forward
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-pause" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>pause
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-pause-circle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>pause-circle
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-pause-circle-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>pause-circle-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-play" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>play
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-play-circle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>play-circle
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-play-circle-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>play-circle-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-random" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>random
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-step-backward" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>step-backward
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-step-forward" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>step-forward
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-stop" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>stop
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-stop-circle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>stop-circle
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-stop-circle-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>stop-circle-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-youtube-play" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>youtube-play
                                    </div>
                                </div>
                            </section>
                            <section id="brand">
                                <h2 class="page-header">Brand Icons</h2>
                                <div class="row fontawesome-icon-list margin-bottom-lg">
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-500px" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>500px
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-adn" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>adn
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-amazon" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>amazon
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-android" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>android
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-angellist" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>angellist
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-apple" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>apple
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bandcamp" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bandcamp
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-behance" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>behance
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-behance-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>behance-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bitbucket" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bitbucket
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bitbucket-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bitbucket-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bitcoin" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bitcoin
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-black-tie" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>black-tie
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bluetooth" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bluetooth
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-bluetooth-b" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>bluetooth-b
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-btc" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>btc
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-buysellads" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>buysellads
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cc-amex" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cc-amex
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cc-diners-club" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cc-diners-club
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cc-discover" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cc-discover
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cc-jcb" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cc-jcb
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cc-mastercard" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cc-mastercard
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cc-paypal" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cc-paypal
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cc-stripe" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cc-stripe
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-cc-visa" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>cc-visa
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-chrome" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>chrome
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-codepen" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>codepen
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-codiepie" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>codiepie
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-connectdevelop" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>connectdevelop
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-contao" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>contao
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-css3" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>css3
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-dashcube" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>dashcube
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-delicious" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>delicious
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-deviantart" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>deviantart
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-digg" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>digg
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-dribbble" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>dribbble
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-dropbox" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>dropbox
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-drupal" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>drupal
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-edge" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>edge
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-eercast" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>eercast
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-empire" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>empire
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-envira" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>envira
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-etsy" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>etsy
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-expeditedssl" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>expeditedssl
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-fa" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>fa
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-facebook" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>facebook
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-facebook-f" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>facebook-f
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-facebook-official" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>facebook-official
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-facebook-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>facebook-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-firefox" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>firefox
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-first-order" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>first-order
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-flickr" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>flickr
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-font-awesome" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>font-awesome
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-fonticons" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>fonticons
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-fort-awesome" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>fort-awesome
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-forumbee" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>forumbee
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-foursquare" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>foursquare
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-free-code-camp" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>free-code-camp
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-ge" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>ge
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-get-pocket" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>get-pocket
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-gg" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>gg
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-gg-circle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>gg-circle
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-git" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>git
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-git-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>git-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-github" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>github
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-github-alt" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>github-alt
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-github-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>github-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-gitlab" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>gitlab
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-gittip" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>gittip
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-glide" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>glide
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-glide-g" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>glide-g
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-google" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>google
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-google-plus" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>google-plus
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-google-plus-circle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>google-plus-circle
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-google-plus-official" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>google-plus-official
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-google-plus-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>google-plus-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-google-wallet" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>google-wallet
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-gratipay" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>gratipay
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-grav" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>grav
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hacker-news" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hacker-news
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-houzz" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>houzz
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-html5" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>html5
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-imdb" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>imdb
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-instagram" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>instagram
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-internet-explorer" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>internet-explorer
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-ioxhost" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>ioxhost
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-joomla" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>joomla
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-jsfiddle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>jsfiddle
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-lastfm" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>lastfm
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-lastfm-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>lastfm-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-leanpub" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>leanpub
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-linkedin" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>linkedin
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-linkedin-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>linkedin-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-linode" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>linode
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-linux" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>linux
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-maxcdn" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>maxcdn
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-meanpath" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>meanpath
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-medium" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>medium
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-meetup" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>meetup
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-mixcloud" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>mixcloud
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-modx" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>modx
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-odnoklassniki" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>odnoklassniki
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-odnoklassniki-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>odnoklassniki-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-opencart" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>opencart
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-openid" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>openid
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-opera" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>opera
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-optin-monster" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>optin-monster
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-pagelines" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>pagelines
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-paypal" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>paypal
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-pied-piper" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>pied-piper
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-pied-piper-alt" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>pied-piper-alt
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-pied-piper-pp" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>pied-piper-pp
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-pinterest" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>pinterest
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-pinterest-p" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>pinterest-p
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-pinterest-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>pinterest-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-product-hunt" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>product-hunt
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-qq" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>qq
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-quora" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>quora
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-ra" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>ra
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-ravelry" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>ravelry
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-rebel" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>rebel
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-reddit" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>reddit
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-reddit-alien" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>reddit-alien
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-reddit-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>reddit-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-renren" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>renren
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-resistance" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>resistance
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-safari" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>safari
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-scribd" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>scribd
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-sellsy" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>sellsy
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-share-alt" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>share-alt
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-share-alt-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>share-alt-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-shirtsinbulk" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>shirtsinbulk
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-simplybuilt" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>simplybuilt
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-skyatlas" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>skyatlas
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-skype" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>skype
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-slack" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>slack
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-slideshare" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>slideshare
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-snapchat" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>snapchat
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-snapchat-ghost" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>snapchat-ghost
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-snapchat-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>snapchat-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-soundcloud" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>soundcloud
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-spotify" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>spotify
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-stack-exchange" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>stack-exchange
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-stack-overflow" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>stack-overflow
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-steam" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>steam
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-steam-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>steam-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-stumbleupon" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>stumbleupon
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-stumbleupon-circle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>stumbleupon-circle
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-superpowers" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>superpowers
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-telegram" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>telegram
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-tencent-weibo" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>tencent-weibo
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-themeisle" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>themeisle
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-trello" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>trello
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-tripadvisor" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>tripadvisor
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-tumblr" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>tumblr
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-tumblr-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>tumblr-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-twitch" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>twitch
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-twitter" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>twitter
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-twitter-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>twitter-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-usb" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>usb
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-viacoin" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>viacoin
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-viadeo" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>viadeo
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-viadeo-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>viadeo-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-vimeo" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>vimeo
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-vimeo-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>vimeo-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-vine" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>vine
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-vk" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>vk
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-wechat" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>wechat
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-weibo" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>weibo
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-weixin" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>weixin
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-whatsapp" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>whatsapp
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-wikipedia-w" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>wikipedia-w
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-windows" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>windows
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-wordpress" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>wordpress
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-wpbeginner" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>wpbeginner
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-wpexplorer" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>wpexplorer
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-wpforms" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>wpforms
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-xing" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>xing
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-xing-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>xing-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-y-combinator" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>y-combinator
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-y-combinator-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>y-combinator-square
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-yahoo" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>yahoo
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-yc" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>yc
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-yc-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>yc-square
                                        <span class="text-muted">(alias)</span>

                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-yelp" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>yelp
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-yoast" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>yoast
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-youtube" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>youtube
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-youtube-play" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>youtube-play
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-youtube-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>youtube-square
                                    </div>
                                </div>
                            </section>
                            <section id="medical">
                                <h2 class="page-header">Medical Icons</h2>
                                <div class="row fontawesome-icon-list">
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-ambulance" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>ambulance
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-h-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>h-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-heart" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>heart
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-heart-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>heart-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-heartbeat" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>heartbeat
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-hospital-o" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>hospital-o
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-medkit" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>medkit
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-plus-square" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>plus-square
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-stethoscope" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>stethoscope
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-user-md" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>user-md
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-wheelchair" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>wheelchair
                                    </div>
                                    <div class="fa-hover col-md-3 col-sm-4">

                                        <i class="fa fa-wheelchair-alt" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>wheelchair-alt
                                    </div>
                                </div>
                            </section>

                        </div>
                        <!-- /#fa-icons -->

                        <!-- glyphicons-->
                        <div class="tab-pane" id="glyphicons">

                            <ul class="bs-glyphicons">
                                <li>
                                    <span class="glyphicon glyphicon-asterisk"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-asterisk</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-plus"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-plus</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-euro"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-euro</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-eur"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-eur</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-minus"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-minus</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-cloud"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-cloud</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-envelope"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-envelope</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-pencil"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-pencil</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-glass"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-glass</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-music"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-music</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-search"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-search</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-heart"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-heart</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-star"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-star</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-star-empty"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-star-empty</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-user"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-user</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-film"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-film</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-th-large"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-th-large</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-th"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-th</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-th-list"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-th-list</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-ok"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-ok</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-remove"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-remove</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-zoom-in"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-zoom-in</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-zoom-out"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-zoom-out</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-off"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-off</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-signal"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-signal</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-cog"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-cog</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-trash"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-trash</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-home"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-home</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-file"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-file</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-time"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-time</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-road"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-road</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-download-alt"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-download-alt</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-download"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-download</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-upload"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-upload</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-inbox"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-inbox</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-play-circle"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-play-circle</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-repeat"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-repeat</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-refresh"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-refresh</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-list-alt"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-list-alt</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-lock"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-lock</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-flag"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-flag</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-headphones"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-headphones</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-volume-off"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-volume-off</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-volume-down"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-volume-down</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-volume-up"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-volume-up</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-qrcode"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-qrcode</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-barcode"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-barcode</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-tag"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-tag</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-tags"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-tags</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-book"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-book</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-bookmark"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-bookmark</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-print"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-print</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-camera"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-camera</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-font"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-font</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-bold"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-bold</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-italic"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-italic</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-text-height"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-text-height</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-text-width"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-text-width</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-align-left"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-align-left</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-align-center"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-align-center</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-align-right"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-align-right</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-align-justify"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-align-justify</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-list"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-list</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-indent-left"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-indent-left</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-indent-right"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-indent-right</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-facetime-video"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-facetime-video</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-picture"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-picture</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-map-marker"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-map-marker</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-adjust"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-adjust</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-tint"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-tint</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-edit"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-edit</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-share"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-share</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-check"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-check</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-move"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-move</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-step-backward"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-step-backward</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-fast-backward"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-fast-backward</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-backward"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-backward</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-play"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-play</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-pause"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-pause</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-stop"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-stop</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-forward"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-forward</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-fast-forward"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-fast-forward</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-step-forward"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-step-forward</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-eject"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-eject</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-chevron-left"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-chevron-left</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-chevron-right"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-chevron-right</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-plus-sign"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-plus-sign</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-minus-sign"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-minus-sign</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-remove-sign"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-remove-sign</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-ok-sign"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-ok-sign</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-question-sign"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-question-sign</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-info-sign"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-info-sign</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-screenshot"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-screenshot</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-remove-circle"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-remove-circle</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-ok-circle"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-ok-circle</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-ban-circle"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-ban-circle</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-arrow-left"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-arrow-left</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-arrow-right"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-arrow-right</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-arrow-up"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-arrow-up</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-arrow-down"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-arrow-down</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-share-alt"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-share-alt</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-resize-full"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-resize-full</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-resize-small"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-resize-small</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-exclamation-sign"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-exclamation-sign</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-gift"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-gift</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-leaf"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-leaf</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-fire"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-fire</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-eye-open</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-eye-close"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-eye-close</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-warning-sign"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-warning-sign</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-plane"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-plane</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-calendar"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-calendar</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-random"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-random</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-comment"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-comment</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-magnet"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-magnet</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-chevron-up"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-chevron-up</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-chevron-down"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-chevron-down</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-retweet"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-retweet</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-shopping-cart"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-shopping-cart</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-folder-close"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-folder-close</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-folder-open"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-folder-open</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-resize-vertical"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-resize-vertical</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-resize-horizontal"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-resize-horizontal</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-hdd"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-hdd</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-bullhorn"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-bullhorn</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-bell"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-bell</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-certificate"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-certificate</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-thumbs-up"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-thumbs-up</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-thumbs-down"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-thumbs-down</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-hand-right"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-hand-right</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-hand-left"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-hand-left</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-hand-up"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-hand-up</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-hand-down"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-hand-down</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-circle-arrow-right"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-circle-arrow-right</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-circle-arrow-left"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-circle-arrow-left</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-circle-arrow-up"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-circle-arrow-up</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-circle-arrow-down"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-circle-arrow-down</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-globe"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-globe</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-wrench"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-wrench</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-tasks"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-tasks</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-filter"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-filter</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-briefcase"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-briefcase</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-fullscreen"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-fullscreen</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-dashboard"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-dashboard</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-paperclip"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-paperclip</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-heart-empty"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-heart-empty</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-link"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-link</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-phone"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-phone</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-pushpin"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-pushpin</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-usd"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-usd</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-gbp"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-gbp</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-sort"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-sort</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-sort-by-alphabet"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-sort-by-alphabet</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-sort-by-alphabet-alt"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-sort-by-alphabet-alt</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-sort-by-order"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-sort-by-order</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-sort-by-order-alt"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-sort-by-order-alt</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-sort-by-attributes"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-sort-by-attributes</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-sort-by-attributes-alt</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-unchecked"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-unchecked</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-expand"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-expand</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-collapse-down"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-collapse-down</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-collapse-up"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-collapse-up</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-log-in"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-log-in</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-flash"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-flash</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-log-out"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-log-out</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-new-window"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-new-window</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-record"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-record</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-save"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-save</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-open"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-open</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-saved"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-saved</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-import"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-import</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-export"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-export</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-send"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-send</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-floppy-disk"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-floppy-disk</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-floppy-saved"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-floppy-saved</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-floppy-remove"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-floppy-remove</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-floppy-save"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-floppy-save</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-floppy-open"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-floppy-open</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-credit-card"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-credit-card</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-transfer"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-transfer</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-cutlery"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-cutlery</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-header"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-header</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-compressed"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-compressed</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-earphone"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-earphone</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-phone-alt"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-phone-alt</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-tower"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-tower</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-stats"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-stats</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-sd-video"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-sd-video</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-hd-video"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-hd-video</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-subtitles"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-subtitles</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-sound-stereo"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-sound-stereo</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-sound-dolby"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-sound-dolby</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-sound-5-1"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-sound-5-1</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-sound-6-1"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-sound-6-1</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-sound-7-1"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-sound-7-1</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-copyright-mark"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-copyright-mark</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-registration-mark"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-registration-mark</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-cloud-download"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-cloud-download</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-cloud-upload"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-cloud-upload</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-tree-conifer"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-tree-conifer</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-tree-deciduous"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-tree-deciduous</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-cd"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-cd</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-save-file"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-save-file</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-open-file"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-open-file</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-level-up"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-level-up</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-copy"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-copy</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-paste"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-paste</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-alert"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-alert</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-equalizer"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-equalizer</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-king"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-king</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-queen"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-queen</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-pawn"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-pawn</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-bishop"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-bishop</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-knight"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-knight</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-baby-formula"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-baby-formula</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-tent"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-tent</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-blackboard"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-blackboard</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-bed"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-bed</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-apple"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-apple</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-erase"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-erase</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-hourglass"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-hourglass</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-lamp"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-lamp</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-duplicate"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-duplicate</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-piggy-bank"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-piggy-bank</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-scissors"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-scissors</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-bitcoin"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-bitcoin</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-btc"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-btc</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-xbt"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-xbt</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-yen"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-yen</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-jpy"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-jpy</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-ruble"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-ruble</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-rub"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-rub</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-scale"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-scale</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-ice-lolly"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-ice-lolly</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-ice-lolly-tasted"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-ice-lolly-tasted</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-education"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-education</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-option-horizontal"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-option-horizontal</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-option-vertical"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-option-vertical</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-menu-hamburger"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-menu-hamburger</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-modal-window"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-modal-window</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-oil"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-oil</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-grain"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-grain</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-sunglasses"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-sunglasses</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-text-size"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-text-size</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-text-color"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-text-color</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-text-background"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-text-background</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-object-align-top"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-object-align-top</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-object-align-bottom"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-object-align-bottom</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-object-align-horizontal"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-object-align-horizontal</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-object-align-left"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-object-align-left</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-object-align-vertical"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-object-align-vertical</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-object-align-right"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-object-align-right</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-triangle-right"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-triangle-right</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-triangle-left"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-triangle-left</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-triangle-bottom"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-triangle-bottom</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-triangle-top"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-triangle-top</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-console"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-console</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-superscript"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-superscript</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-subscript"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-subscript</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-menu-left"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-menu-left</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-menu-right"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-menu-right</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-menu-down"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-menu-down</span>
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-menu-up"></span>
                                    <span class="glyphicon-class">glyphicon glyphicon-menu-up</span>
                                </li>
                            </ul>
                        </div>
                        <!-- /#ion-icons -->
                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
        </div>
        <!-- /.col -->
    </div>
</div>
<!-- /.row -->

@endsection