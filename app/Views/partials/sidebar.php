<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" data-key="t-menu"><?= lang('Files.Menu') ?></li>

                <li>
                    <a href="/">
                        <i data-feather="home"></i>
                        <span data-key="t-dashboard"><?= lang('Files.Dashboard') ?></span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="grid"></i>
                        <span data-key="t-apps"><?= lang('Files.Apps') ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="apps-calendar">
                                <span data-key="t-calendar"><?= lang('Files.Calendar') ?></span>
                            </a>
                        </li>
        
                        <li>
                            <a href="apps-chat">
                                <span data-key="t-chat"><?= lang('Files.Chat') ?></span>
                            </a>
                        </li>
        
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <span data-key="t-email"><?= lang('Files.Email') ?></span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="apps-email-inbox" data-key="t-inbox"><?= lang('Files.Inbox') ?></a></li>
                                <li><a href="apps-email-read" data-key="t-read-email"><?= lang('Files.Read_Email') ?></a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <span data-key="t-invoices"><?= lang('Files.Invoices') ?></span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="apps-invoices-list" data-key="t-invoice-list"><?= lang('Files.Invoice_List') ?></a></li>
                                <li><a href="apps-invoices-detail" data-key="t-invoice-detail"><?= lang('Files.Invoice_Detail') ?></a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <span data-key="t-contacts"><?= lang('Files.Contacts') ?></span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="apps-contacts-grid" data-key="t-user-grid"><?= lang('Files.User_Grid') ?></a></li>
                                <li><a href="apps-contacts-list" data-key="t-user-list"><?= lang('Files.User_List') ?></a></li>
                                <li><a href="apps-contacts-profile" data-key="t-profile"><?= lang('Files.Profile') ?></a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="users"></i>
                        <span data-key="t-authentication"><?= lang('Files.Authentication') ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="auth-login" data-key="t-login"><?= lang('Files.Login') ?></a></li>
                        <li><a href="auth-register" data-key="t-register"><?= lang('Files.Register') ?></a></li>
                        <li><a href="auth-recoverpw" data-key="t-recover-password"><?= lang('Files.Recover_Password') ?></a></li>
                        <li><a href="auth-lock-screen" data-key="t-lock-screen"><?= lang('Files.Lock_Screen') ?> </a></li>
                        <li><a href="auth-confirm-mail" data-key="t-confirm-mail"><?= lang('Files.Confirm_Mail') ?></a></li>
                        <li><a href="auth-email-verification" data-key="t-email-verification"><?= lang('Files.Email_Verification') ?></a></li>
                        <li><a href="auth-two-step-verification" data-key="t-two-step-verification"><?= lang('Files.Two_Step_Verification') ?></a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="file-text"></i>
                        <span data-key="t-pages"><?= lang('Files.Pages') ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="pages-starter" data-key="t-starter-page"><?= lang('Files.Starter_Page') ?></a></li>
                        <li><a href="pages-maintenance" data-key="t-maintenance"><?= lang('Files.Maintenance') ?></a></li>
                        <li><a href="pages-comingsoon" data-key="t-coming-soon"><?= lang('Files.Coming_Soon') ?></a></li>
                        <li><a href="pages-timeline" data-key="t-timeline"><?= lang('Files.Timeline') ?></a></li>
                        <li><a href="pages-faqs" data-key="t-faqs"><?= lang('Files.FAQs') ?></a></li>
                        <li><a href="pages-pricing" data-key="t-pricing"><?= lang('Files.Pricing') ?></a></li>
                        <li><a href="pages-404" data-key="t-error-404"><?= lang('Files.Error_404') ?></a></li>
                        <li><a href="pages-500" data-key="t-error-500"><?= lang('Files.Error_500') ?></a></li>
                    </ul>
                </li>

                <li>
                    <a href="layouts-horizontal">
                        <i data-feather="layout"></i>
                        <span data-key="t-horizontal"><?= lang('Files.Horizontal') ?></span>
                    </a>
                </li>

                <li class="menu-title mt-2" data-key="t-components"><?= lang('Files.Elements') ?></li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="briefcase"></i>
                        <span data-key="t-components"><?= lang('Files.Components') ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="ui-alerts" data-key="t-alerts"><?= lang('Files.Alerts') ?></a></li>
                        <li><a href="ui-buttons" data-key="t-buttons"><?= lang('Files.Buttons') ?></a></li>
                        <li><a href="ui-cards" data-key="t-cards"><?= lang('Files.Cards') ?></a></li>
                        <li><a href="ui-carousel" data-key="t-carousel"><?= lang('Files.Carousel') ?></a></li>
                        <li><a href="ui-dropdowns" data-key="t-dropdowns"><?= lang('Files.Dropdowns') ?></a></li>
                        <li><a href="ui-grid" data-key="t-grid"><?= lang('Files.Grid') ?></a></li>
                        <li><a href="ui-images" data-key="t-images"><?= lang('Files.Images') ?></a></li>
                        <li><a href="ui-modals" data-key="t-modals"><?= lang('Files.Modals') ?></a></li>
                        <li><a href="ui-offcanvas" data-key="t-offcanvas"><?= lang('Files.Offcanvas') ?></a></li>
                        <li><a href="ui-progressbars" data-key="t-progress-bars"><?= lang('Files.Progress_Bars') ?></a></li>
                        <li><a href="ui-tabs-accordions" data-key="t-tabs-accordions"><?= lang('Files.Tabs_n_Accordions') ?></a></li>
                        <li><a href="ui-typography" data-key="t-typography"><?= lang('Files.Typography') ?></a></li>
                        <li><a href="ui-video" data-key="t-video"><?= lang('Files.Video') ?></a></li>
                        <li><a href="ui-general" data-key="t-general"><?= lang('Files.General') ?></a></li>
                        <li><a href="ui-colors" data-key="t-colors"><?= lang('Files.Colors') ?></a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="gift"></i>
                        <span data-key="t-ui-elements"><?= lang('Files.Extended') ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="extended-lightbox" data-key="t-lightbox"><?= lang('Files.Lightbox') ?></a></li>
                        <li><a href="extended-rangeslider" data-key="t-range-slider"><?= lang('Files.Range_Slider') ?></a></li>
                        <li><a href="extended-sweet-alert" data-key="t-sweet-alert"><?= lang('Files.SweetAlert_2') ?></a></li>
                        <li><a href="extended-session-timeout" data-key="t-session-timeout"><?= lang('Files.Session_Timeout') ?></a></li>
                        <li><a href="extended-rating" data-key="t-rating"><?= lang('Files.Rating') ?></a></li>
                        <li><a href="extended-notifications" data-key="t-notifications"><?= lang('Files.Notifications') ?></a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);">
                        <i data-feather="box"></i>
                        <span class="badge rounded-pill bg-soft-danger text-danger float-end">7</span>
                        <span data-key="t-forms"><?= lang('Files.Forms') ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="form-elements" data-key="t-form-elements"><?= lang('Files.Basic_Elements') ?></a></li>
                        <li><a href="form-validation" data-key="t-form-validation"><?= lang('Files.Validation') ?></a></li>
                        <li><a href="form-advanced" data-key="t-form-advanced"><?= lang('Files.Advanced_Plugins') ?></a></li>
                        <li><a href="form-editors" data-key="t-form-editors"><?= lang('Files.Editors') ?></a></li>
                        <li><a href="form-uploads" data-key="t-form-upload"><?= lang('Files.File_Upload') ?></a></li>
                        <li><a href="form-wizard" data-key="t-form-wizard"><?= lang('Files.Wizard') ?></a></li>
                        <li><a href="form-mask" data-key="t-form-mask"><?= lang('Files.Mask') ?></a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="sliders"></i>
                        <span data-key="t-tables"><?= lang('Files.Tables') ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="tables-basic" data-key="t-basic-tables"><?= lang('Files.Bootstrap_Basic') ?></a></li>
                        <li><a href="tables-datatable" data-key="t-data-tables"><?= lang('Files.DataTables') ?></a></li>
                        <li><a href="tables-responsive" data-key="t-responsive-table"><?= lang('Files.Responsive') ?></a></li>
                        <li><a href="tables-editable" data-key="t-editable-table"><?= lang('Files.Editable') ?></a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="pie-chart"></i>
                        <span data-key="t-charts"><?= lang('Files.Charts') ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="charts-apex" data-key="t-apex-charts"><?= lang('Files.Apexcharts') ?></a></li>
                        <li><a href="charts-echart" data-key="t-e-charts"><?= lang('Files.Echarts') ?></a></li>
                        <li><a href="charts-chartjs" data-key="t-chartjs-charts"><?= lang('Files.Chartjs') ?></a></li>
                        <li><a href="charts-knob" data-key="t-knob-charts"><?= lang('Files.Jquery_Knob') ?></a></li>
                        <li><a href="charts-sparkline" data-key="t-sparkline-charts"><?= lang('Files.Sparkline') ?></a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="cpu"></i>
                        <span data-key="t-icons"><?= lang('Files.Icons') ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="icons-boxicons" data-key="t-boxicons"><?= lang('Files.Boxicons') ?></a></li>
                        <li><a href="icons-materialdesign" data-key="t-material-design"><?= lang('Files.Material_Design') ?></a></li>
                        <li><a href="icons-dripicons" data-key="t-dripicons"><?= lang('Files.Dripicons') ?></a></li>
                        <li><a href="icons-fontawesome" data-key="t-font-awesome"><?= lang('Files.Font_Awesome_5') ?></a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="map"></i>
                        <span data-key="t-maps"><?= lang('Files.Maps') ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="maps-google" data-key="t-g-maps"><?= lang('Files.Google') ?></a></li>
                        <li><a href="maps-vector" data-key="t-v-maps"><?= lang('Files.Vector') ?></a></li>
                        <li><a href="maps-leaflet" data-key="t-l-maps"><?= lang('Files.Leaflet') ?></a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="share-2"></i>
                        <span data-key="t-multi-level"><?= lang('Files.Multi_Level') ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="javascript: void(0);" data-key="t-level-1-1"><?= lang('Files.Level_1_1') ?></a></li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow" data-key="t-level-1-2"><?= lang('Files.Level_1_2') ?></a>
                            <ul class="sub-menu" aria-expanded="true">
                                <li><a href="javascript: void(0);" data-key="t-level-2-1"><?= lang('Files.Level_2_1') ?></a></li>
                                <li><a href="javascript: void(0);" data-key="t-level-2-2"><?= lang('Files.Level_2_2') ?></a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

            </ul>

            <div class="card sidebar-alert border-0 text-center mx-4 mb-0 mt-5">
                <div class="card-body">
                    <img src="assets/images/giftbox.png" alt="">
                    <div class="mt-4">
                        <h5 class="alertcard-title font-size-16"><?= lang('Files.Unlimited_Access') ?></h5>
                        <p class="font-size-13"><?= lang("Files.Upgrade_your_plan_from_a_Free_trial,_to_select_‘Business_Plan’") ?>.</p>
                        <a href="#!" class="btn btn-primary mt-2"><?= lang('Files.Upgrade_Now') ?></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->