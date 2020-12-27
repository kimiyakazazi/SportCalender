<?php
include 'DataBase.php';
include 'databaseCfg.php';
include 'EventFactory.php';
include 'Event.php';
include 'TeamFactory.php';
include 'Team.php';
include 'CategoryFactory.php';
include 'Category.php';
include "Location.php";
include "LocationFactory.php";

if (isset($_POST['func']) && !empty($_POST['func'])) {
    switch ($_POST['func']) {
        case 'getCalender':
            getCalender($_POST['year'], $_POST['month'], $_POST['team'], $_POST['category']);
            break;
        case 'getEvents':
            getEvents($_POST['date'], $_POST['team'], $_POST['category']);
            break;
        case 'addEvent':
            addEvent($_POST['date'], $_POST['time'], $_POST['category'], $_POST['first_team'], $_POST['second_team'], $_POST['location']);
            break;
        default:
            break;
    }
}

/*
 * Generate event calendar in HTML format
 */
function getCalender($year = '', $month = '', $currentTeam = '', $currentCategory = '')
{
    $dateYear = ($year != '') ? $year : date("Y");
    $dateMonth = ($month != '') ? $month : date("m");
    $date = $dateYear . '-' . $dateMonth . '-01';
    $currentMonthFirstDay = date("N", strtotime($date));
    $totalDaysOfMonth = cal_days_in_month(CAL_GREGORIAN, $dateMonth, $dateYear);
    $totalDaysOfMonthDisplay = ($currentMonthFirstDay == 1) ? ($totalDaysOfMonth) : ($totalDaysOfMonth + ($currentMonthFirstDay - 1));
    $boxDisplay = ($totalDaysOfMonthDisplay <= 35) ? 35 : 42;
    $prevMonth = date("m", strtotime('-1 month', strtotime($date)));
    $prevYear = date("Y", strtotime('-1 month', strtotime($date)));
    $totalDaysOfMonth_Prev = cal_days_in_month(CAL_GREGORIAN, $prevMonth, $prevYear);
    ?>
    <main class="calendar-contain">
        <section class="title-bar">
            <a href="javascript:void(0);" class="title-bar__prev"
               onclick="getCalendar('calendar_div','<?php echo date("Y", strtotime($date . ' - 1 Month')); ?>','<?php echo date("m", strtotime($date . ' - 1 Month')); ?>','<?php echo $currentTeam; ?>','<?php echo $currentCategory; ?>');"></a>
            <div class="title-bar__month">
                <select class="month-dropdown">
                    <?php echo getMonthList($dateMonth); ?>
                </select>
            </div>
            <div class="title-bar__year">
                <select class="year-dropdown">
                    <?php echo getYearList($dateYear); ?>
                </select>
            </div>
            <div class="title-bar__Team">
                <select class="team-dropdown">
                    <?php echo getTeamList($currentTeam, false); ?>
                </select>
            </div>
            <div class="title-bar__Category">
                <select class="category-dropdown">
                    <?php echo getCategoryList($currentCategory, false); ?>
                </select>
            </div>
            <a href="javascript:void(0);" class="title-bar__next"
               onclick="getCalendar('calendar_div','<?php echo date("Y", strtotime($date . ' + 1 Month')); ?>','<?php echo date("m", strtotime($date . ' + 1 Month')); ?>','<?php echo $currentTeam; ?>','<?php echo $currentCategory; ?>');"></a>
        </section>
        <button type="button"  style="width: 30%" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Add event</button>
        <!-- Modal -->
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add Event</h4>
                    </div>
                    <div class="modal-body">
                        <form action="index.php" method="post" onsubmit="addEvent(document.getElementById('select_date').value,document.getElementById('select_time').value,document.getElementById('select_category').value,document.getElementById('select_first_team').value,document.getElementById('select_second_team').value,document.getElementById('select_location').value)">
                            <table>
                                <tr>
                                    <td>Category</td>
                                    <td>First_team</td>
                                    <td>Second_team</td>
                                    <td>Date</td>
                                    <td>Time</td>
                                    <td>Location</td>
                                </tr>
                                <tbody>
                                <tr>
                                    <td>
                                        <select name="select_category" id="select_category">
                                            <?php echo getCategoryList($currentCategory, true); ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="select_first_team" id="select_first_team">
                                            <?php echo getTeamList($currentCategory, true); ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="select_second_team" id="select_second_team">
                                            <?php echo getTeamList($currentCategory, true); ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="date" name="select_date" id="select_date"/>
                                    </td>
                                    <td>
                                        <input type="time" name="select_time" id="select_time"/>
                                    </td>
                                    <td>
                                        <select name="select_location" id="select_location">
                                            <?php echo getLocationList(); ?>
                                        </select>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="modal-footer">
                                <input type="submit" class="btn btn-default"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <aside class="calendar__sidebar" id="event_list">
            <?php echo getEvents(); ?>
        </aside>

        <section class="calendar__days">
            <section class="calendar__top-bar">
                <span class="top-bar__days">Mon</span>
                <span class="top-bar__days">Tue</span>
                <span class="top-bar__days">Wed</span>
                <span class="top-bar__days">Thu</span>
                <span class="top-bar__days">Fri</span>
                <span class="top-bar__days">Sat</span>
                <span class="top-bar__days">Sun</span>
            </section>

            <?php
            $dayCount = 1;
            $eventNum = 0;

            echo '<section class="calendar__week">';
            for ($cb = 1; $cb <= $boxDisplay; $cb++) {
                if (($cb >= $currentMonthFirstDay || $currentMonthFirstDay == 1) && $cb <= ($totalDaysOfMonthDisplay)) {
                    // Current date
                    $currentDate = $dateYear . '-' . $dateMonth . '-' . $dayCount;

                    $eventNum = count(EventFactory::getEvents($currentDate, $currentTeam, $currentCategory));

                    // Define date cell color
                    if (strtotime($currentDate) == strtotime(date("Y-m-d"))) {
                        echo ' 
                                <div class="calendar__day today" onclick="getEvents(\'' . $currentDate . '\',\'' . $currentTeam . '\',\'' . $currentCategory . '\');"> 
                                    <span class="calendar__date">' . $dayCount . '</span> 
                                    <span class="calendar__task calendar__task--today">' . $eventNum .' Events</span> 
                                </div> 
                            ';
                    } elseif ($eventNum > 0) {
                        echo ' 
                                <div class="calendar__day event" onclick="getEvents(\'' . $currentDate . '\',\'' . $currentTeam . '\',\'' . $currentCategory . '\');"> 
                                    <span class="calendar__date">' . $dayCount . '</span> 
                                    <span class="calendar__task">' . $eventNum . ' Events</span> 
                                </div> 
                            ';
                    } else {
                        echo ' 
                                <div class="calendar__day no-event" onclick="getEvents(\'' . $currentDate . '\',\'' . $currentTeam . '\',\'' . $currentCategory . '\');"> 
                                    <span class="calendar__date">' . $dayCount . '</span> 
                                    <span class="calendar__task">' . $eventNum . ' Events</span> 
                                </div> 
                            ';
                    }
                    $dayCount++;
                } else {
                    if ($cb < $currentMonthFirstDay) {
                        $inactiveCalendarDay = ((($totalDaysOfMonth_Prev - $currentMonthFirstDay) + 1) + $cb);
                    } else {
                        $inactiveCalendarDay = ($cb - $totalDaysOfMonthDisplay);
                    }
                    echo ' 
                            <div class="calendar__day inactive"> 
                                <span class="calendar__date">' . $inactiveCalendarDay . '</span> 
                            </div> 
                        ';
                }
                echo ($cb % 7 == 0 && $cb != $boxDisplay) ? '</section><section class="calendar__week">' : '';
            }
            echo '</section>';
            ?>
        </section>
    </main>

    <script>
        function getCalendar(target_div, year, month, team, category) {
            $.ajax({
                type: 'POST',
                url: 'functions.php',
                data: 'func=getCalender&year=' + year + '&month=' + month + '&team=' + team + '&category=' + category,
                success: function (html) {
                    $('#' + target_div).html(html);
                }
            });
        }

        function addEvent(date, time, category, first_team, second_team, location) {
            $.ajax({
                type: 'POST',
                url: 'functions.php',
                data: 'func=addEvent&date=' + date + '&time=' + time + '&category=' + category + '&first_team=' + first_team + '&second_team=' + second_team+ '&location=' + location,
                success: handleData(String(date))
            });
        }

        function handleData(data) {
            //do some stuff
        }

        function getEvents(date, team, category) {
            $.ajax({
                type: 'POST',
                url: 'functions.php',
                data: 'func=getEvents&date=' + date + '&team=' + team + '&category=' + category,
                success: function (html) {
                    $('#event_list').html(html);
                }
            });
        }

        $(document).ready(function () {
            $('.month-dropdown').on('change', function () {
                getCalendar('calendar_div', $('.year-dropdown').val(), $('.month-dropdown').val(), $('.team-dropdown').val(), $('.category-dropdown').val());
            });
            $('.year-dropdown').on('change', function () {
                getCalendar('calendar_div', $('.year-dropdown').val(), $('.month-dropdown').val(), $('.team-dropdown').val(), $('.category-dropdown').val());
            });
            $('.team-dropdown').on('change', function () {
                getCalendar('calendar_div', $('.year-dropdown').val(), $('.month-dropdown').val(), $('.team-dropdown').val(), $('.category-dropdown').val());
            });
            $('.category-dropdown').on('change', function () {
                getCalendar('calendar_div', $('.year-dropdown').val(), $('.month-dropdown').val(), $('.team-dropdown').val(), $('.category-dropdown').val());
            });
        });
    </script>
    <?php
}

/*
 * Generate months options list for select box
 */
function getMonthList($selected = '')
{
    $options = '';
    for ($i = 1; $i <= 12; $i++) {
        $value = ($i < 10) ? '0' . $i : $i;
        $selectedOpt = ($value == $selected) ? 'selected' : '';
        $options .= '<option value="' . $value . '" ' . $selectedOpt . ' >' . date("F", mktime(0, 0, 0, $i + 1, 0, 0)) . '</option>';
    }
    return $options;
}

/*
 * Generate team list for select box
 */
function getTeamList($selected = '', $add)
{
    $teams = '';
    $listTeam = TeamFactory::getTeams();
    if($add) {
        unset($listTeam[0]);
    }
    foreach ($listTeam as $i => $team) {
        $selectedOpt = ($team->id == $selected) ? 'selected' : '';
        $teams .= '<option value="' . $team->id . '" ' . $selectedOpt . '>' . $team->name . '</option>';
    }
    return $teams;
}

/*
 * Generate team list for select box
 */
function getLocationList()
{
    $locations = '';
    $listLocation = LocationFactory::getLocations();
    foreach ($listLocation as $i => $location) {
        $locations .= '<option value="' . $location->id . '">' . $location->name . '</option>';
    }
    return $locations;
}

/*
 * Generate category list for select box
 */
function getCategoryList($selected = '', $add)
{
    $categories = '';
    $listCategories = CategoryFactory::getCategories();
    if ($add) {
        unset($listCategories[0]);
    }
    foreach ($listCategories as $i => $category) {
        $selectedOpt = ($category->id == $selected) ? 'selected' : '';
        $categories .= '<option value="' . $category->id . '" ' . $selectedOpt . '>' . $category->name . '</option>';
    }
    return $categories;
}

/*
 * Generate years options list for select box
 */
function getYearList($selected = '')
{
    $yearInit = !empty($selected) ? $selected : date("Y");
    $yearPrev = ($yearInit - 5);
    $yearNext = ($yearInit + 5);
    $options = '';
    for ($i = $yearPrev; $i <= $yearNext; $i++) {
        $selectedOpt = ($i == $selected) ? 'selected' : '';
        $options .= '<option value="' . $i . '" ' . $selectedOpt . ' >' . $i . '</option>';
    }
    return $options;
}

/*
 * Generate events list in HTML format
 */
function getEvents($date = '', $team = '', $category = '')
{
    $date = $date ? $date : date("Y-m-d");

    $eventListHTML = '<h2 class="sidebar__heading">' . date("l", strtotime($date)) . '<br>' . date("F d", strtotime($date)) . '</h2>';

    // Fetch events based on the specific date
    $list = EventFactory::getEvents($date, $team, $category);
    if (count($list) > 0) {
        $eventListHTML .= '<ul class="sidebar__list">';
        $eventListHTML .= '<li class="sidebar__list-item sidebar__list-item--complete">Events</li>';

        foreach ($list as $i => $event) {
            $i++;
            $eventListHTML .= '<l class="sidebar__list-item">' . $event . '</li></br></br>';
        }
        $eventListHTML .= '</ul>';
    }
    echo $eventListHTML;
}

function addEvent($eventDate='',$eventTime='',$eventCategory='',$eventFirstTeam='',$eventSecondTeam='',$eventLocation='')
{
    EventFactory::addEvent($eventDate,$eventTime,$eventCategory,$eventFirstTeam,$eventSecondTeam,$eventLocation);
}