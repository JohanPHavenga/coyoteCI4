<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [
        'session',
        'form',
        'formulate',
        'filesystem',
        'auth',
        'url',
        'text_format',
        'cookie'
    ];
    protected $edition_model;
    protected $status_list;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        // global edition model        
        $this->edition_model = model(EditionModel::class);
        // set data_to_views as empty array
        $this->data_to_views['menus'] = $this->get_menus();;
        // dd($this->data_to_views['menus']);
        // create session
        $this->session = \Config\Services::session();
        // check for flash data alert
        if (isset($_SESSION['alert_msg'])) {
            $this->data_to_views['flash_data'] = $this->session->getFlashdata();
        }

        // get status array
        $this->status_list = $this->edition_model->get_status_array();

        // set province dropdown        
        $province_model = model(ProvinceModel::class);
        // $this->data_to_views['province_list']=$province_model->list();
        $this->data_to_views['province_options'][0] = "National";
        foreach ($province_model->list() as $province) {
            $this->data_to_views['province_options'][$province['province_id']] = $province['province_name'];
        }

        // dd(user());

        if (!isset($_SESSION['site_province'])) {
            if (logged_in()) {
                $_SESSION['site_province'] = user()->province;
            } else {
                if (has_cookie('site_province')) {
                    $_SESSION['site_province'] = get_cookie('site_province');
                } else {
                    $_SESSION['site_province'] = 0;
                }
            }
        }
        if (!isset($_SESSION['sort_by'])) {
            if (has_cookie('sort_by')) {
                $_SESSION['sort_by'] = get_cookie('sort_by');
            } else {
                $_SESSION['sort_by'] = 'date';
            }
        }

        // set user avatar
        $this->data_to_views['user_avatar'] = base_url('assets/images/user-avatar-placeholder.png');
        $this->data_to_views['user_status'] = 'offline';
        if (logged_in()) {
            if (user()->picture) {
                $this->data_to_views['user_avatar'] = user()->picture;
            }
            $this->data_to_views['user_status'] = 'online';
            $this->data_to_views['user_roles'] = user()->getRoles();
        }

        // set default breadcrumbs
        $this->data_to_views['bc_arr'] = $this->get_crumbs();
        // set default title
        end($this->data_to_views['bc_arr']);
        $this->data_to_views['page_title'] = key($this->data_to_views['bc_arr']);


        // validation on search - remove funny characters
        if ($this->request->getGet('s')) {
            $rules = ['s' => ['label'  => 'Search', 'rules'  => 'alpha_numeric_punct',],];
            if (!$this->validate($rules)) {
                $this->data_to_views['search_string'] = preg_replace("/[^A-Za-z0-9 ]/", '', $this->request->getGet('s'));
            } else {
                $this->data_to_views['search_string'] = $this->request->getGet('s');
            }
        } else {
            $this->data_to_views['search_string'] = '';
        }

        // transparent header for home page
        $this->data_to_views['transparent_header']=false;
    }

    public function send_email($att)
    {
        // need to always send NAME, EMAIL and MESSAGE
        $email = \Config\Services::email();
        if (isset($att['to'])) {
            $to = $att['to'];
        } else {
            $to = 'info@roadrunning.co.za';
        }
        if (isset($att['subject'])) {
            $subject = $att['subject'];
        } else {
            $subject = 'Website Contact: ' . $att['name'];
        }

        $email->setTo($to);
        $email->setCC("info@roadrunning.co.za");
        $email->setFrom("webmaster@roadrunning.co.za", $att['name']);
        $email->setReplyTo($att['email'], $att['name']);
        $email->setSubject($subject);
        $email->setMessage($att['message']);

        if ($email->send()) {
            return true;
        } else {
            $data = $email->printDebugger(['headers']);
            // dd($data);
            return false;
        }
    }

    public function get_crumbs()
    {
        // setup auto crumbs from URI
        $uri = new \CodeIgniter\HTTP\URI(current_url());

        $segs = $uri->getSegments();
        // dd($segs);
        $crumb_uri = base_url();
        $total_segments = $uri->getTotalSegments();
        $crumbs['Home'] = base_url();
        for ($x = 0; $x < $total_segments; $x++) {

            if (($x == $total_segments) || ($x == 3)) {
                $crumb_uri = "";
            } else {
                $crumb_uri .= "/" . $segs[$x];
            }

            // make controller prural for event and overwrite URI
            if (($x == 1) && ($segs[$x] == "event")) {
                $segs[$x] = "race";
            }
            // make controller prural for display purposes
            if (in_array($segs[$x], ["race"])) {
                $segs[$x] = $segs[$x] . "s";
            }

            $segs[$x] = str_replace("_", " ", $segs[$x]);
            $segs[$x] = str_replace("-", " ", $segs[$x]);
            $segs[$x] = str_replace("%20", " ", $segs[$x]);
            $crumbs[ucwords($segs[$x])] = $crumb_uri;

            if ($x == 3) {
                break;
            }
        }

        return $crumbs;
    }

    public function get_menus()
    {
        $menus['static_pages'] = $this->get_static_pages();
        $remove = ['switch-region', 'featured-regions', 'login', 'add-listing', 'search', 'sitemap', 'terms', 'disclaimer', 'home', 'privacy'];
        $menus['main_menu'] = array_diff_key($menus['static_pages'], array_flip($remove));
        if (logged_in()) {
            $menus['user_menu'] = $this->get_user_menu();
        }
        return $menus;
    }

    public function get_static_pages()
    {
        return [
            "home" => [
                "display" => "Home",
                "loc" => base_url(),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 day")),
                "priority" => 1,
                "changefreq" => "daily",
            ],
            "races" => [
                "display" => "Races",
                "loc" => base_url("calendar"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-2 day")),
                "priority" => 1,
                "changefreq" => "daily",
                "sub-menu" => [
                    "upcoming" => [
                        "display" => "Upcoming",
                        "loc" => base_url("race/upcoming"), //calendar
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-2 day")),
                        "priority" => 1,
                        "changefreq" => "daily",
                        "badge" => "POPULAR",
                    ],
                    "favourite" => [
                        "display" => "Bookmarked",
                        "loc" => base_url("race/favourite"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-2 day")),
                        "priority" => 1,
                        "changefreq" => "daily",
                        "badge" => "BETA",
                    ],
                    "featured" => [
                        "display" => "Featured",
                        "loc" => base_url("race/featured"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-2 day")),
                        "priority" => 1,
                        "changefreq" => "daily",
                    ],
                    "province" => [
                        "display" => "Per Province",
                        "loc" => base_url("province"), //calendar/past
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-2 day")),
                        "priority" => 0.8,
                        "changefreq" => "daily",
                    ],
                    "parkrun" => [
                        "display" => "parkrun",
                        "loc" => base_url("race/parkrun"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                        "priority" => 0.3,
                        "changefreq" => "yearly",
                    ],
                ],
            ],
            "results" => [
                "display" => "Results",
                "loc" => base_url("results"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-5 day")),
                "priority" => 0.8,
                "changefreq" => "weekly",
                "sub-menu" => [
                    "results" => [
                        "display" => "Race Results",
                        "loc" => base_url("race/results"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-5 day")),
                        "priority" => 0.8,
                        "changefreq" => "weekly",
                    ],
                    "my-results" => [
                        "display" => "My Results",
                        "loc" => "",
                        "loc" => base_url("my-results"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-5 day")),
                        "priority" => 0.8,
                        "changefreq" => "weekly",
                        "badge" => "BETA",
                    ],
                ],
            ],
            "resources" => [
                "display" => "Resources",
                "loc" => base_url("faq"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-5 day")),
                "priority" => 0.8,
                "changefreq" => "weekly",
                "sub-menu" => [
                    "faq" => [
                        "display" => "FAQ",
                        "loc" => base_url("faq"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 month")),
                        "priority" => 0.5,
                        "changefreq" => "monthly",
                    ],
                    "training" => [
                        "display" => "Training Programs",
                        "loc" => base_url("training-programs"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 month")),
                        "priority" => 0.5,
                        "changefreq" => "monthly",
                    ],
                    "friends" => [
                        "display" => "Friends",
                        "loc" => base_url("friends"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 month")),
                        "priority" => 0.5,
                        "changefreq" => "monthly",
                    ],
                ],
            ],
            "contact" => [
                "display" => "Contact",
                "loc" => base_url("contact"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                "priority" => 0.8,
                "changefreq" => "yearly",
                "sub-menu" => [
                    "contact-us" => [
                        "display" => "Contact Us",
                        "loc" => base_url("contact"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                        "priority" => 0.8,
                        "changefreq" => "yearly",
                    ],
                    "about" => [
                        "display" => "About",
                        "loc" => base_url("about"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 month")),
                        "priority" => 0.5,
                        "changefreq" => "monthly",
                    ],
                    "support" => [
                        "display" => "Support the site",
                        "loc" => base_url("support"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                        "priority" => 0.8,
                        "changefreq" => "yearly",
                    ],
                    "newsletter" => [
                        "display" => "Newsletter",
                        "loc" => base_url("newsletter"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                        "priority" => 0.6,
                        "changefreq" => "yearly",
                        "badge" => "POPULAR",
                    ],
                ],
            ],
            "switch-region" => [
                "display" => "Switch Region",
                "loc" => base_url("region/switch"), //version
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                "priority" => 0.5,
                "changefreq" => "yearly",
            ],
            "featured-regions" => [
                "display" => "Show all regions",
                "loc" => base_url("region"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 month")),
                "priority" => 0.8,
                "changefreq" => "monthly",
                "sub-menu" => [
                    "cape-town" => [
                        "display" => "Cape Town",
                        "loc" => base_url("region/capetown"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-5 day")),
                        "priority" => 1,
                        "changefreq" => "weekly",
                        "badge" => "POPULAR",
                    ],
                    "gauteng" => [
                        "display" => "Gauteng",
                        "loc" => base_url("region/gauteng"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-5 day")),
                        "priority" => 0.8,
                        "changefreq" => "weekly",
                    ],
                    "kzn-coast" => [
                        "display" => "KZN Coast",
                        "loc" => base_url("region/kzn-coast"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-5 day")),
                        "priority" => 0.8,
                        "changefreq" => "weekly",
                    ],
                    "garden-route" => [
                        "display" => "Garden Route",
                        "loc" => base_url("region/garden-route"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-5 day")),
                        "priority" => 0.8,
                        "changefreq" => "weekly",
                    ],
                ],
            ],
            "login" => [
                "display" => "Login",
                "loc" => base_url("login"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                "priority" => 1,
                "changefreq" => "yearly",
            ],
            "add-listing" => [
                "display" => "Add Race Listing",
                "loc" => base_url("event/add"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                "priority" => 0.6,
                "changefreq" => "yearly",
            ],
            "search" => [
                "display" => "Search",
                "loc" => base_url("search"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 week")),
                "priority" => 0.8,
                "changefreq" => "weekly",
            ],
            "sitemap" => [
                "display" => "Sitemap",
                "loc" => base_url("sitemap"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 week")),
                "priority" => 0.5,
                "changefreq" => "daily",
            ],
            "terms" => [
                "display" => "Terms & Conditions",
                "loc" => base_url("terms-conditions"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                "priority" => 0.2,
                "changefreq" => "yearly",
            ],
            "disclaimer" => [
                "display" => "Disclaimer",
                "loc" => base_url("disclaimer"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                "priority" => 0.2,
                "changefreq" => "yearly",
            ],
            "privacy" => [
                "display" => "Privacy Policy",
                "loc" => base_url("privacy-policy"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                "priority" => 0.2,
                "changefreq" => "yearly",
            ],
        ];
    }

    public function get_user_menu()
    {
        $menu_arr = [
            "dashboard" => [
                "display" => "Dashboard",
                "loc" => base_url("user/dashboard"),
                "icon" => "icon-material-outline-dashboard",
            ],
            "profile" => [
                "display" => "Profile",
                "loc" => base_url("user/profile"),
                "icon" => "icon-material-outline-face",
            ],
            "favourite" => [
                "display" => "Favourites",
                "loc" => base_url("race/favourite"),
                "icon" => "icon-material-outline-favorite",
            ],
            "results" => [
                "display" => "Results",
                "loc" => base_url("my-results"),
                "icon" => "icon-material-outline-assignment",
            ],
            "subscriptions" => [
                "display" => "Subscriptions",
                "loc" => base_url("user/my-subscriptions"),
                "icon" => "icon-material-baseline-mail-outline",
            ],
            "donate" => [
                "display" => "Donate",
                "loc" => base_url("user/donate"),
                "icon" => "icon-material-outline-local-atm",
            ],
            "logout" => [
                "display" => "Logout",
                "loc" => base_url("logout"),
                "icon" => "icon-material-outline-power-settings-new",
            ],
        ];
        return $menu_arr;
    }

    public function subscribe_user($user_data, $linked_to, $linked_id)
    {
        // this function will add a user to a subscription        
        $user_model = model(UserModelExtended::class);
        $usersubscription_model = model(UserSubscriptionModel::class);

        // get user id
        $user_id = $user_model->get_user_id($user_data['email']);
        // new user
        if (!$user_id) {
            $user_id = $user_model->set_user($user_data, $user_id, [2]);
        } else {
            // check if role 2 (user) exist
            // if not, add
        }

        // check if subscription exists
        $sub_exists = $usersubscription_model->exists($user_id, $linked_to, $linked_id);
        if ($sub_exists) {
            $alert = "There is already a subsciption linked to the email address for this event";
            $status = "warning";
            $icon = "info-circle";
        } else {
            $usersub_data = array(
                'user_id' => $user_id,
                'linked_to' => $linked_to,
                'linked_id' => $linked_id,
            );

            $add = $usersubscription_model->set_usersub($usersub_data);
            if ($add) {
                switch ($linked_to) {
                    case "edition":
                    case "event":
                        $alert = "Thank you. You have been added to the mailing list for this race";
                        break;
                    case "newsletter":
                        $alert = "Thank you. You have successfully been subscribed to the newsletter";
                        break;
                    default:
                        $alert = "Thank you. You have successfully been subscribed";
                        break;
                }
                $status = "success";
                $icon = "check-circle";
            } else {
                $alert = "Failed to add subsciprtion. Please contact the site administrator";
                $status = "danger";
                $icon = "minus-circle";
            }
        }
        if (!$this->request->isAJAX()) {
            // set session flash data if not posted via AJAX
            $this->session->setFlashdata([
                'alert_msg' => $alert,
                'alert_status' => $status,
                'alert_icon' => $icon,
            ]);
        }
    }

    public function status_notice_list()
    {
        return [
            2 => [
                "msg" => "<b>This event is set to DRAFT mode.</b> All detail has not yet been confirmed",
                "short_msg" => "DRAFT",
                "state" => "cancelled",
            ],
            3 => [
                "msg" => "<strong>This event has been CANCELLED.</strong> Please use the event contact form  to contact the event organisers for more detail.",
                "short_msg" => "Cancelled",
                "state" => "cancelled",
            ],
            9 => [
                "msg" => "<strong>This event has been POSTPONED until further notice.</strong> Please contact the event organisers for more detail<br>"
                    . "Please consider <b><a href='#subscribe'>subscribing</a></b> to the event below to receive an email once a new date is set",
                "short_msg" => "Postposed",
                "state" => "unverified",
            ],
            13 => [
                "msg" => "<strong>PLEASE NOTE</strong> - Dates and race times has <u>not yet been confirmed</u> by the race organisers",
                "short_msg" => "Unconfimred",
                "state" => "unverified",
            ],
            14 => [
                "msg" => "<b>DATE CONFIRMED</b> - Waiting for race information from the organisers",
                "short_msg" => "Dates Confirmed",
                "state" => "pending",
            ],
            15 => [
                "msg" => "<b>EVENT CONFIRMED</b> - Information loaded has been confirmed as correct. Awaiting for complete information set from the organisers",
                "short_msg" => "Awaiting Info",
                "state" => "confirmed",
            ],
            16 => [
                "msg" => "<b>LISTING VERIFIED</b> - All information below has been confirmed",
                "short_msg" => "Verified",
                "state" => "verified",
            ],
            10 => [
                "msg" => "<b>RESULTS PENDING</b> - Waiting for results to be released.<br>Note this can take up to a week",
                "short_msg" => "Results Pending",
                "state" => "confirmed",
            ],
            11 => [
                "msg" => "<b>RESULTS LOADED</b> - Click on link below to view",
                "short_msg" => "Results Loaded",
                "state" => "verified",
            ],
            12 => [
                "msg" => "<b>NO RESULTS EXPECTED</b> - No official results will be released for this event",
                "short_msg" => "No Results Expexted",
                "state" => "unverified",
            ],
        ];
    }

    public function formulate_status_notice($status, $info_status)
    {
        $status_notice_list = $this->status_notice_list();
        $return = [];

        switch ($status) {
            case 2:
            case 3:
            case 9:
                $msg = $status_notice_list[$status]['msg'];
                $short_msg = $status_notice_list[$status]['short_msg'];
                $state = $status_notice_list[$status]['state'];
                break;
            default:
                switch ($info_status) {
                    case 13:
                    case 14:
                    case 15:
                    case 16:
                    case 10:
                    case 11:
                    case 12:
                        $msg = $status_notice_list[$info_status]['msg'];
                        $short_msg = $status_notice_list[$info_status]['short_msg'];
                        $state = $status_notice_list[$info_status]['state'];
                        break;
                }
                break;
        }

        $return['msg'] = $msg;
        $return['short_msg'] = $short_msg;
        $return['state'] = $state;
        // dd($return);
        return $return;
    }

    public function get_timeago($date = false)
    {
        if (isset($date)) {
            $timeAgo = strtotime($date);
            $curTime = time();
            $timeElapsed = $curTime - $timeAgo;
            $seconds = $timeElapsed;
            $minutes = round($timeElapsed / 60);
            $hours = round($timeElapsed / 3600);
            $days = round($timeElapsed / 86400);
            $weeks = round($timeElapsed / 604800);
            $months = round($timeElapsed / 2600640);
            $years = round($timeElapsed / 31207680);
            /* Seconds  Calculation*/
            if ($seconds <= 60) {
                return 'Just Now';
            } /* Minutes */ elseif ($minutes <= 60) {
                if ($minutes == 1) {
                    return "One Minute ago";
                } else {
                    return $minutes . " time ago minutes";
                }
            } /* Hours */ elseif ($hours <= 24) {
                if ($hours == 1) {
                    return "One hour ago";
                } else {
                    return $hours . " Hours ago";
                }
            } /* Days */ elseif ($days <= 7) {
                if ($days == 1) {
                    return "One day ago";
                } else {
                    return $days . " days ago";
                }
            } /* Weeks */ elseif ($weeks <= 4.3) {
                if ($weeks == 1) {
                    return "One week ago";
                } else {
                    return $weeks . " weeks ago";
                }
            } /* Months */ elseif ($months <= 12) {
                if ($months == 1) {
                    return "One month ago";
                } else {
                    return $months . " months ago";
                }
            } /* Years */ else {
                if ($years == 1) {
                    return "One year ago";
                } else {
                    return $years . " years ago";
                }
            }
        } else {
            return "Not yet";
        }
    }

    private function has_auto_mail_been_send($emailtemplate_id, $edition_id)
    {
        $this->load->model('autoemail_model');
        if ($this->autoemail_model->exists($emailtemplate_id, $edition_id)) {
            return true;
        } else {
            return false;
        }
    }

    public function sort_picker()
    {
        $_SESSION['sort_by'] = $this->request->getPost('sort_by_picker');
        set_cookie("sort_by", $this->request->getPost('sort_by_picker'), 172800);
        return redirect()->back();
    }

    public function replace_key($arr, $oldkey, $newkey) {
        if(array_key_exists( $oldkey, $arr)) {
            $keys = array_keys($arr);
            $keys[array_search($oldkey, $keys)] = $newkey;
            return array_combine($keys, $arr);	
        }
        return $arr;
    }

    // to be converted
    public function auto_mailer($emailtemplate_id = 0, $edition_id = 0)
    {
        $this->load->model('autoemail_model');
        $this->load->model('edition_model');
        $this->load->model('admin/emailtemplate_model');
        $this->load->model('admin/emailmerge_model');
        $this->load->model('admin/usersubscription_model');
        // get edition detail
        $edition_detail = $this->edition_model->get_edition_detail($edition_id);
        // get email temaplte list
        $emailtemplate_list = $this->emailtemplate_model->get_emailtemplate_list();

        // security check
        if ((!array_key_exists($emailtemplate_id, $emailtemplate_list)) || (!$edition_detail)) {
            $this->session->set_flashdata([
                'alert' => " Mmmmm, somthing is not right.",
                'status' => "danger",
            ]);
            redirect(base_url("404"), 'refresh', 404);
            die();
        }
        // check of mails mag gestuur word
        if ($edition_detail['edition_no_auto_mail']) {
            return false;
        }
        // doen check of mail alreeds gestuur is
        if ($this->has_auto_mail_been_send($emailtemplate_id, $edition_id)) {
            return false;
        }
        // main code
        $user_arr = $this->usersubscription_model->get_usersubscription_list("edition", $edition_id);
        if (!$user_arr) {
            return false;
        } else {
            foreach ($user_arr as $user) {
                $user_list[] = $user['user_id'];
            }
        }
        $user_str = implode(",", $user_list);
        $emailtemplate = $this->emailtemplate_model->get_emailtemplate_detail($emailtemplate_id);

        // create email merge
        $merge_data = array(
            "emailmerge_status" => 4,
            "emailmerge_subject" => $emailtemplate['emailtemplate_name'] . " " . $edition_detail['edition_name'],
            "emailmerge_body" => $emailtemplate['emailtemplate_body'],
            "emailmerge_recipients" => $user_str,
            "emailmerge_linked_to" => "edition",
            "linked_id" => $edition_id,
        );
        $emailmerge_id = $this->emailmerge_model->set_emailmerge("add", 0, $merge_data);
        $autoemail_id = $this->autoemail_model->set_autoemail($emailtemplate_id, $edition_id);

        return true;
    }

    public function createThumbnail($filepath, $thumbpath, $thumbnail_width, $thumbnail_height, $background = false)
    {
        list($original_width, $original_height, $original_type) = getimagesize($filepath);
        if ($original_width > $original_height) {
            $new_width = $thumbnail_width;
            $new_height = intval($original_height * $new_width / $original_width);
        } else {
            $new_height = $thumbnail_height;
            $new_width = intval($original_width * $new_height / $original_height);
        }
        $dest_x = intval(($thumbnail_width - $new_width) / 2);
        $dest_y = intval(($thumbnail_height - $new_height) / 2);

        if ($original_type === 1) {
            $imgt = "ImageGIF";
            $imgcreatefrom = "ImageCreateFromGIF";
        } else if ($original_type === 2) {
            $imgt = "ImageJPEG";
            $imgcreatefrom = "ImageCreateFromJPEG";
        } else if ($original_type === 3) {
            $imgt = "ImagePNG";
            $imgcreatefrom = "ImageCreateFromPNG";
            $background = 'transparent';
            // dd($filepath);
        } else {
            return false;
        }

        $old_image = $imgcreatefrom($filepath);
        $new_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height); // creates new image, but with a black background

        // figuring out the color for the background
        if (is_array($background) && count($background) === 3) {
            list($red, $green, $blue) = $background;
            $color = imagecolorallocate($new_image, $red, $green, $blue);
            imagefill($new_image, 0, 0, $color);
            // apply transparent background only if is a png image
        } else if ($background === 'transparent' && $original_type === 3) {
            imagesavealpha($new_image, TRUE);
            $color = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
            imagefill($new_image, 0, 0, $color);
        }

        imagecopyresampled($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $original_width, $original_height);
        $imgt($new_image, $thumbpath);
        return file_exists($thumbpath);
    }
}
