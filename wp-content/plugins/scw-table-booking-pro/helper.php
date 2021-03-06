<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-config.php');
global $wpdb;

$task = $_POST["task"];

if ($task == "add_room") {
    $roomName = filter_var($_POST["roomName"], FILTER_SANITIZE_STRING);

    $roomsTb = $wpdb->prefix . 'scwatbwsr_rooms';
    $getdtSql = $wpdb->prepare("SELECT * from {$roomsTb} where roomname = %s", $roomName);
    $rs = $wpdb->get_results($getdtSql);

    if ($rs) {
        echo "This room already exists!";
    } else {
        $wpdb->query($wpdb->prepare("INSERT INTO $roomsTb (roomname)
		VALUES (%s)",
            $roomName));
    }
} elseif ($task == "save_base_setting") {
    $roomId = filter_var($_POST["roomId"], FILTER_VALIDATE_INT);
    $width = filter_var($_POST["width"], FILTER_SANITIZE_STRING);
    $height = filter_var($_POST["height"], FILTER_SANITIZE_STRING);
    $newRoomname = filter_var($_POST["newRoomname"], FILTER_SANITIZE_STRING);
    $color = filter_var($_POST["color"], FILTER_SANITIZE_STRING);
    $bg = filter_var($_POST["bg"], FILTER_SANITIZE_STRING);
    $tbbookedcolor = filter_var($_POST["tbbookedcolor"], FILTER_SANITIZE_STRING);

    $roomsTb = $wpdb->prefix . 'scwatbwsr_rooms';
    $wpdb->query($wpdb->prepare("UPDATE $roomsTb SET roomname=%s, roomcolor=%s, roombg=%s, width=%s, height=%s, tbbookedcolor=%s WHERE id=%d",
        $newRoomname, $color, $bg, $width, $height, $tbbookedcolor, $roomId));
} elseif ($task == "add_type") {
    $roomId = filter_var($_POST["roomId"], FILTER_VALIDATE_INT);
    $typename = filter_var($_POST["typename"], FILTER_SANITIZE_STRING);
    $tbbg = filter_var($_POST["tbbg"], FILTER_SANITIZE_STRING);
    $tbshape = filter_var($_POST["tbshape"], FILTER_SANITIZE_STRING);
    $tbrecwidth = filter_var($_POST["tbrecwidth"], FILTER_SANITIZE_STRING);
    $tbrecheight = filter_var($_POST["tbrecheight"], FILTER_SANITIZE_STRING);
    $tbcirwidth = filter_var($_POST["tbcirwidth"], FILTER_SANITIZE_STRING);
    $maxppl = filter_var($_POST["maxppl"], FILTER_SANITIZE_STRING);

    $typesTb = $wpdb->prefix . 'scwatbwsr_types';
    $getdtSql = $wpdb->prepare("SELECT * from {$typesTb} where roomid = %s and name=%s", $roomId, $typename);
    $rs = $wpdb->get_results($getdtSql);

    if ($rs) {
        echo "This type already exists!";
    } else {
        $wpdb->query($wpdb->prepare("INSERT INTO $typesTb (roomid, name, tbbg, tbshape, tbrecwidth, tbrecheight, tbcirwidth, maxppl)
		VALUES (%d, %s, %s, %s, %s, %s, %s, %s)", $roomId, $typename, $tbbg, $tbshape, $tbrecwidth, $tbrecheight, $tbcirwidth, $maxppl));
    }
} elseif ($task == "save_type") {
    $thistypeid = filter_var($_POST["thistypeid"], FILTER_VALIDATE_INT);
    $thistbcolor = filter_var($_POST["thistbcolor"], FILTER_SANITIZE_STRING);
    $thistbrecwidth = filter_var($_POST["thistbrecwidth"], FILTER_SANITIZE_STRING);
    $thistbrecheight = filter_var($_POST["thistbrecheight"], FILTER_SANITIZE_STRING);
    $thistbcirwidth = filter_var($_POST["thistbcirwidth"], FILTER_SANITIZE_STRING);
    $maxppl = filter_var($_POST["maxppl"], FILTER_SANITIZE_STRING);

    $typesTb = $wpdb->prefix . 'scwatbwsr_types';
    $wpdb->query($wpdb->prepare("UPDATE $typesTb SET tbbg=%s, tbrecwidth=%s, tbrecheight=%s, tbcirwidth=%s, maxppl=%s WHERE id=%d",
        $thistbcolor, $thistbrecwidth, $thistbrecheight, $thistbcirwidth, $maxppl, $thistypeid));
} elseif ($task == "delete_type") {
    $thistypeid = filter_var($_POST["thistypeid"], FILTER_VALIDATE_INT);

    $typesTb = $wpdb->prefix . 'scwatbwsr_types';
    $wpdb->query($wpdb->prepare("DELETE FROM $typesTb where id=%d", $thistypeid));
} elseif ($task == "add_schedule") {
    $roomId = filter_var($_POST["roomId"], FILTER_VALIDATE_INT);
    $schedule = filter_var($_POST["schedule"], FILTER_SANITIZE_STRING);

    $schedulesTb = $wpdb->prefix . 'scwatbwsr_schedules';
    $wpdb->query($wpdb->prepare("INSERT INTO $schedulesTb (roomid, schedule)
	VALUES (%d, %s)",
        $roomId, $schedule));
} elseif ($task == "save_schedule") {
    $scheid = filter_var($_POST["scheid"], FILTER_VALIDATE_INT);
    $thisschedule = filter_var($_POST["thisschedule"], FILTER_SANITIZE_STRING);

    $schedulesTb = $wpdb->prefix . 'scwatbwsr_schedules';
    $wpdb->query($wpdb->prepare("UPDATE $schedulesTb SET schedule=%s WHERE id=%d",
        $thisschedule, $scheid));
} elseif ($task == "delete_schedule") {
    $scheid = filter_var($_POST["scheid"], FILTER_VALIDATE_INT);

    $schedulesTb = $wpdb->prefix . 'scwatbwsr_schedules';
    $wpdb->query($wpdb->prepare("DELETE FROM $schedulesTb where id=%d", $scheid));
} elseif ($task == "change_daily") {
    $roomId = filter_var($_POST["roomId"], FILTER_VALIDATE_INT);
    $dailys = filter_var($_POST["dailys"], FILTER_SANITIZE_STRING);
    $dailys = implode(",", $_POST["dailys"]);

    $dailyScheTb = $wpdb->prefix . 'scwatbwsr_dailyschedules';
    $getdtSql = $wpdb->prepare("SELECT * from {$dailyScheTb} where roomid=%s", $roomId);
    $rs = $wpdb->get_results($getdtSql);

    if ($rs) {
        $wpdb->query($wpdb->prepare("UPDATE $dailyScheTb SET daily=%s WHERE roomid=%d",
            $dailys, $roomId));
    } else {
        $wpdb->query($wpdb->prepare("INSERT INTO $dailyScheTb (roomid, daily)
		VALUES (%d, %s)",
            $roomId, $dailys));
    }
} elseif ($task == "add_time") {
    $roomId = filter_var($_POST["roomId"], FILTER_VALIDATE_INT);
    $scheduletime = filter_var($_POST["scheduletime"], FILTER_SANITIZE_STRING);

    $dailyTimeTb = $wpdb->prefix . 'scwatbwsr_dailytimes';
    $getdtSql = $wpdb->prepare("SELECT * from {$dailyTimeTb} where roomid=%s and time=%s", $roomId, $scheduletime);
    $rs = $wpdb->get_results($getdtSql);

    if ($rs) {
        echo "This time already exists!";
    } else {
        $wpdb->query($wpdb->prepare("INSERT INTO $dailyTimeTb (roomid, time)
		VALUES (%d, %s)",
            $roomId, $scheduletime));
    }
} elseif ($task == "save_time") {
    $thistimeid = filter_var($_POST["thistimeid"], FILTER_VALIDATE_INT);
    $thistimetime = filter_var($_POST["thistimetime"], FILTER_SANITIZE_STRING);

    $tableName = $wpdb->prefix . 'scwatbwsr_dailytimes';
    $wpdb->query($wpdb->prepare("UPDATE $tableName SET time=%s WHERE id=%d",
        $thistimetime, $thistimeid));
} elseif ($task == "delete_time") {
    $thistimeid = filter_var($_POST["thistimeid"], FILTER_VALIDATE_INT);

    $tableName = $wpdb->prefix . 'scwatbwsr_dailytimes';
    $wpdb->query($wpdb->prepare("DELETE FROM $tableName where id=%d", $thistimeid));
} elseif ($task == "save_price") {
    $priceString = filter_var($_POST["priceString"], FILTER_SANITIZE_STRING);
    $priceString = explode("@", $priceString);

    $pricesTb = $wpdb->prefix . 'scwatbwsr_prices';

    foreach ($priceString as $price) {
        if ($price) {
            $cprice = explode("-", $price);

            if (!$cprice[1] || !is_numeric($cprice[1])) $pri = 0;
            else $pri = $cprice[1];

            $getdtSql = $wpdb->prepare("SELECT * from {$pricesTb} where typeid=%d", $cprice[0]);
            $rs = $wpdb->get_results($getdtSql);

            if ($rs) {
                $wpdb->query($wpdb->prepare("UPDATE $pricesTb SET price=%s, type=%s WHERE typeid=%d",
                    $pri, $cprice[2], $cprice[0]));
            } else {
                $wpdb->query($wpdb->prepare("INSERT INTO $pricesTb (typeid, price, type)
				VALUES (%d, %s, %s)",
                    $cprice[0], $pri, $cprice[2]));
            }
        }
    }
} elseif ($task == "add_table") {
    $roomId = filter_var($_POST["roomId"], FILTER_VALIDATE_INT);
    $label = filter_var($_POST["label"], FILTER_SANITIZE_STRING);
    //$seats = filter_var($_POST["seats"], FILTER_SANITIZE_STRING);
    $type = filter_var($_POST["type"], FILTER_SANITIZE_STRING);

    $tablesTb = $wpdb->prefix . 'scwatbwsr_tables';
    $getdtSql = $wpdb->prepare("SELECT * from {$tablesTb} where roomid = %d and label=%s", $roomId, $label);
    $rs = $wpdb->get_results($getdtSql);

    if ($rs) {
        echo "This table already exists!";
    } else {
        $wpdb->query($wpdb->prepare("INSERT INTO $tablesTb (roomid, label, type)
		VALUES (%d, %s, %d)",
            $roomId, $label, $type));
    }
} elseif ($task == "save_table") {
    $thistbid = filter_var($_POST["thistbid"], FILTER_VALIDATE_INT);
    $thistbseats = filter_var($_POST["thistbseats"], FILTER_SANITIZE_STRING);
    $thistbtype = filter_var($_POST["thistbtype"], FILTER_VALIDATE_INT);

    $tableName = $wpdb->prefix . 'scwatbwsr_tables';
    $wpdb->query($wpdb->prepare("UPDATE $tableName SET seats=%s, type=%d WHERE id=%d",
        $thistbseats, $thistbtype, $thistbid));
} elseif ($task == "delete_table") {
    $thistbid = filter_var($_POST["thistbid"], FILTER_VALIDATE_INT);

    $tableName = $wpdb->prefix . 'scwatbwsr_tables';
    $wpdb->query($wpdb->prepare("DELETE FROM $tableName where id=%d", $thistbid));
} elseif ($task == "save_mapping") {
    $tbstring = filter_var($_POST["tbstring"], FILTER_SANITIZE_STRING);
    $tbstring = explode("@", $tbstring);
    //$seatstring = filter_var($_POST["seatstring"], FILTER_SANITIZE_STRING);
    //$seatstring = explode("@", $seatstring);

    $tableName = $wpdb->prefix . 'scwatbwsr_tables';
    $tableSeat = $wpdb->prefix . 'scwatbwsr_seats';

    foreach ($tbstring as $tb) {
        $tbdt = explode("#", $tb);

        $wpdb->query($wpdb->prepare("UPDATE $tableName SET tleft=%s, ttop=%s WHERE id=%d",
            $tbdt[1], $tbdt[2], $tbdt[0]));
    }

//    foreach ($seatstring as $st) {
//        $checkdt = explode("#", $st);
//
//        $tbid = $checkdt[0];
//        $seatdts = explode("&", $checkdt[1]);
//
//        foreach ($seatdts as $seatdt) {
//            $sdt = explode("$", $seatdt);
//
//            $getdtSql = $wpdb->prepare("SELECT * from {$tableSeat} where tbid = %d and seat=%s", $tbid, $sdt[0]);
//            $rs = $wpdb->get_results($getdtSql);
//
//            if ($rs) {
//                $wpdb->query($wpdb->prepare("UPDATE $tableSeat SET tleft=%s, ttop=%s WHERE tbid=%d and seat=%s",
//                    $sdt[1], $sdt[2], $tbid, $sdt[0]));
//            } else {
//                $wpdb->query($wpdb->prepare("INSERT INTO $tableSeat (tbid, seat, tleft, ttop)
//				VALUES (%d, %s, %s, %s)",
//                    $tbid, $sdt[0], $sdt[1], $sdt[2]));
//            }
//        }
//    }
} elseif ($task == "save_product_profile") {
    $proid = filter_var($_POST["proid"], FILTER_VALIDATE_INT);
    $vl = filter_var($_POST["vl"], FILTER_VALIDATE_INT);

    $tableName = $wpdb->prefix . 'scwatbwsr_products';
    $getrs = $wpdb->prepare("SELECT * from {$tableName} where proid=%d", $proid);
    $rs = $wpdb->get_results($getrs);

    if ($rs) {
        $wpdb->query($wpdb->prepare("UPDATE {$tableName} SET roomid=%d where proid=%d",
            $vl, $proid));
    } else {
        $wpdb->query($wpdb->prepare("INSERT INTO $tableName (roomid, proid)
		VALUES (%d, %d)",
            $vl, $proid));
    }
} elseif ($task == "copy_room") {
    $roomId = filter_var($_POST["roomId"], FILTER_VALIDATE_INT);
    $newname = filter_var($_POST["newname"], FILTER_SANITIZE_STRING);

    $tableRooms = $wpdb->prefix . 'scwatbwsr_rooms';

    $getMaxid = $wpdb->prepare("SELECT MAX(id) maxid from {$tableRooms} where %d", 1);
    $getMaxidRs = $wpdb->get_results($getMaxid);
    $maxid = $getMaxidRs[0]->maxid + 1;

    $getRoomData = $wpdb->prepare("SELECT * from {$tableRooms} where id=%d", $roomId);
    $roomData = $wpdb->get_results($getRoomData);
    if ($roomData) {
        $wpdb->query($wpdb->prepare("INSERT INTO $tableRooms (id, roomname, roomcolor, roombg, width, height, tbbookedcolor, seatbookedcolor)
		VALUES (%d, %s, %s, %s, %s, %s, %s, %s)",
            $maxid, $newname, $roomData[0]->roomcolor, $roomData[0]->roombg, $roomData[0]->width, $roomData[0]->height, $roomData[0]->tbbookedcolor, $roomData[0]->seatbookedcolor));
    }

    $tableDailySche = $wpdb->prefix . 'scwatbwsr_dailyschedules';
    $getDailySche = $wpdb->prepare("SELECT * from {$tableDailySche} where roomid=%d", $roomId);
    $dailySche = $wpdb->get_results($getDailySche);
    if ($dailySche) {
        $wpdb->query($wpdb->prepare("INSERT INTO $tableDailySche (roomid, daily)
		VALUES (%d, %s)",
            $maxid, $dailySche[0]->daily));
    }

    $tableDailyTime = $wpdb->prefix . 'scwatbwsr_dailytimes';
    $getDailyTime = $wpdb->prepare("SELECT * from {$tableDailyTime} where roomid=%d", $roomId);
    $dailyTime = $wpdb->get_results($getDailyTime);
    if ($dailyTime) {
        foreach ($dailyTime as $dt) {
            $wpdb->query($wpdb->prepare("INSERT INTO $tableDailyTime (roomid, time)
			VALUES (%d, %s)",
                $maxid, $dt->time));
        }
    }

    $tableSches = $wpdb->prefix . 'scwatbwsr_schedules';
    $getSches = $wpdb->prepare("SELECT * from {$tableSches} where roomid=%d", $roomId);
    $sches = $wpdb->get_results($getSches);
    if ($sches) {
        foreach ($sches as $sche) {
            $wpdb->query($wpdb->prepare("INSERT INTO $tableSches (roomid, schedule)
			VALUES (%d, %s)",
                $maxid, $sche->schedule));
        }
    }

    $tablePrices = $wpdb->prefix . 'scwatbwsr_prices';
    $tableTypes = $wpdb->prefix . 'scwatbwsr_types';
    $getTypes = $wpdb->prepare("SELECT * from {$tableTypes} where roomid=%d", $roomId);
    $types = $wpdb->get_results($getTypes);
    if ($types) {
        $getMaxType = $wpdb->prepare("SELECT MAX(id) maxtypeid from {$tableTypes} where %d", 1);
        $getMaxTypeRs = $wpdb->get_results($getMaxType);
        $maxtype = $getMaxTypeRs[0]->maxtypeid + 1;

        foreach ($types as $type) {
            $wpdb->query($wpdb->prepare("INSERT INTO $tableTypes (id, roomid, name, tbbg, tbshape, tbrecwidth, tbrecheight, tbcirwidth, seatbg, seatshape, seatwidth)
			VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                $maxtype, $maxid, $type->name, $type->tbbg, $type->tbshape, $type->tbrecwidth, $type->tbrecheight, $type->tbcirwidth, $type->seatbg, $type->seatshape, $type->seatwidth));

            $oldid = $type->id;

            $getPrice = $wpdb->prepare("SELECT * from {$tablePrices} where typeid=%d", $oldid);
            $price = $wpdb->get_results($getPrice);
            if ($price) {
                $wpdb->query($wpdb->prepare("INSERT INTO $tablePrices (typeid, price, type)
				VALUES (%d, %s, %s)",
                    $maxtype, $price[0]->price, $price[0]->type));
            }

            $maxtype++;
        }
    }

    $tableTables = $wpdb->prefix . 'scwatbwsr_tables';
    $tableSeats = $wpdb->prefix . 'scwatbwsr_seats';
    $getTables = $wpdb->prepare("SELECT * from {$tableTables} where roomid=%d", $roomId);
    $tables = $wpdb->get_results($getTables);
    if ($tables) {
        $getMaxTable = $wpdb->prepare("SELECT MAX(id) maxtbid from {$tableTables} where %d", 1);
        $getMaxTableRs = $wpdb->get_results($getMaxTable);
        $maxtb = $getMaxTableRs[0]->maxtbid + 1;

        foreach ($tables as $table) {
            $wpdb->query($wpdb->prepare("INSERT INTO $tableTables (id, roomid, label, seats, type, tleft, ttop)
			VALUES (%d, %d, %s, %s, %d, %s, %s)",
                $maxtb, $maxid, $table->label, $table->seats, $table->type, $table->tleft, $table->ttop));

            $oldid = $table->id;

            $getSeat = $wpdb->prepare("SELECT * from {$tableSeats} where tbid=%d", $oldid);
            $seats = $wpdb->get_results($getSeat);
            if ($seats) {
                foreach ($seats as $s) {
                    $wpdb->query($wpdb->prepare("INSERT INTO $tableSeats (tbid, seat, tleft, ttop)
					VALUES (%d, %s, %s, %s)",
                        $maxtb, $s->seat, $s->tleft, $s->ttop));
                }
            }

            $maxtb++;
        }
    }
} elseif ($task == "delete_room") {
    $roomId = filter_var($_POST["roomId"], FILTER_VALIDATE_INT);

    $roomsTb = $wpdb->prefix . 'scwatbwsr_rooms';
    $wpdb->query($wpdb->prepare("DELETE FROM $roomsTb where id=%d", $roomId));

    $typesTB = $wpdb->prefix . 'scwatbwsr_types';
    $pricesTB = $wpdb->prefix . 'scwatbwsr_prices';
    $getTypes = $wpdb->prepare("SELECT * from {$typesTB} where roomid=%d", $roomId);
    $types = $wpdb->get_results($getTypes);
    if ($types) {
        foreach ($types as $type) {
            $wpdb->query($wpdb->prepare("DELETE FROM $pricesTB where typeid=%d", $type->id));
        }
    }
    $wpdb->query($wpdb->prepare("DELETE FROM $typesTB where roomid=%d", $roomId));

    $tablesTB = $wpdb->prefix . 'scwatbwsr_tables';
    $seatsTB = $wpdb->prefix . 'scwatbwsr_seats';
    $getTables = $wpdb->prepare("SELECT * from {$tablesTB} where roomid=%d", $roomId);
    $tables = $wpdb->get_results($getTables);
    if ($tables) {
        foreach ($tables as $tb) {
            $wpdb->query($wpdb->prepare("DELETE FROM $seatsTB where tbid=%d", $tb->id));
        }
    }
    $wpdb->query($wpdb->prepare("DELETE FROM $tablesTB where roomid=%d", $roomId));

    $dlsTB = $wpdb->prefix . 'scwatbwsr_dailyschedules';
    $wpdb->query($wpdb->prepare("DELETE FROM $dlsTB where roomid=%d", $roomId));

    $dltTB = $wpdb->prefix . 'scwatbwsr_dailytimes';
    $wpdb->query($wpdb->prepare("DELETE FROM $dltTB where roomid=%d", $roomId));

    $proTB = $wpdb->prefix . 'scwatbwsr_products';
    $wpdb->query($wpdb->prepare("DELETE FROM $proTB where roomid=%d", $roomId));

    $schedulesTB = $wpdb->prefix . 'scwatbwsr_schedules';
    $wpdb->query($wpdb->prepare("DELETE FROM $schedulesTB where roomid=%d", $roomId));
} elseif ($task == "check_schedule") {
    $proid = filter_var($_POST["proid"], FILTER_VALIDATE_INT);
    $roomid = filter_var($_POST["roomid"], FILTER_VALIDATE_INT);
    $schedule = filter_var($_POST["schedule"], FILTER_SANITIZE_STRING);
    $bookingtime = filter_var($_POST["bookingtime"], FILTER_SANITIZE_STRING);

    $_SESSION["schedule" . $proid] = $schedule;

    $bookedTB = $wpdb->prefix . 'scwatbwsr_bookedseats';
    $ordersTb = $wpdb->prefix . 'scwatbwsr_orders';

    $bookedSeats = array();

    if ($bookingtime) {
        if (get_option('date_format') == "d/m/Y") {
            $cschedule = explode(" ", $schedule);
            $cschedule1 = explode("/", $cschedule[0]);
            $schedule = $cschedule1[2] . "-" . $cschedule1[1] . "-" . $cschedule1[0] . " " . $cschedule[1];
        } elseif (get_option('date_format') == "d-m-Y") {
            $cschedule = explode(" ", $schedule);
            $cschedule1 = explode("-", $cschedule[0]);
            $schedule = $cschedule1[2] . "-" . $cschedule1[1] . "-" . $cschedule1[0] . " " . $cschedule[1];
        }

        for ($i = 0; $i <= $bookingtime; $i += 5) {
            $datesche = date(get_option('date_format') . " H:i", strtotime("-" . $i . " minutes", strtotime($schedule)));
            $getOrdersSql = $wpdb->prepare("SELECT * from {$ordersTb} where productId=%d and schedule=%s", $proid, $datesche);
            $orders = $wpdb->get_results($getOrdersSql);
            if ($orders) {
                foreach ($orders as $order) {
                    $oseats = explode(",", $order->seats);
                    foreach ($oseats as $os) {
                        array_push($bookedSeats, $os);
                    }
                }
            }
        }
    } else {
        $getOrdersSql = $wpdb->prepare("SELECT * from {$ordersTb} where productId=%d and schedule=%s", $proid, $schedule);
        $orders = $wpdb->get_results($getOrdersSql);
        if ($orders) {
            foreach ($orders as $order) {
                $oseats = explode(",", $order->seats);
                foreach ($oseats as $os) {
                    array_push($bookedSeats, $os);
                }
            }
        }
    }

    $getBookedSql = $wpdb->prepare("SELECT * from {$bookedTB} where roomid=%d", $roomid);
    $bookeds = $wpdb->get_results($getBookedSql);
    if ($bookeds) {
        foreach ($bookeds as $bk) {
            array_push($bookedSeats, $bk->tb . "." . $bk->seat);
        }
    }
    $bookedSeats = array_unique($bookedSeats);

    echo json_encode($bookedSeats, 1);
} elseif ($task == "sess_seats") {
    $proid = filter_var($_POST["proid"], FILTER_VALIDATE_INT);
    $seat = filter_var($_POST["seat"], FILTER_SANITIZE_STRING);
    $posttype = filter_var($_POST["posttype"], FILTER_SANITIZE_STRING);

    $_SESSION["seat" . $proid] = $seat;

    if ($posttype == "events") {
        $proTb = $wpdb->prefix . 'scwatbwsr_products';
        $tablesTb = $wpdb->prefix . 'scwatbwsr_tables';
        $pricesTb = $wpdb->prefix . 'scwatbwsr_prices';

        $getRoomSql = $wpdb->prepare("SELECT * from {$proTb} where proid=%d", $proid);
        $room = $wpdb->get_results($getRoomSql);
        $roomid = $room[0]->roomid;

        $total = 0;

        if (isset($_SESSION["seat" . $proid])) {
            $seat = $_SESSION["seat" . $proid];
            $pertbArr = array();
            $onetimeArr = array();

            $getTypeSql = $wpdb->prepare("SELECT * from {$tablesTb} where roomid=%d and label=%s", $roomid, $seat);
            $getType = $wpdb->get_results($getTypeSql);
            $typeid = $getType[0]->type;

            $getPriceSql = $wpdb->prepare("SELECT * from {$pricesTb} where typeid=%d", $typeid);
            $getPrice = $wpdb->get_results($getPriceSql);

            if ($getPrice && $getPrice[0]->price) {
                if ($getPrice[0]->type == "seat") {
                    $total += $getPrice[0]->price;
                } elseif ($getPrice[0]->type == "table") {
                    $pertbArr[] = array(
                        'tb' => $seat,
                        'price' => $getPrice[0]->price
                    );
                } else {
                    $onetimeArr[] = array(
                        'tb' => $seat,
                        'price' => $getPrice[0]->price
                    );
                }
            }

            $pertbArr = array_map("unserialize", array_unique(array_map("serialize", $pertbArr)));
            if ($pertbArr)
                $total += $pertbArr[0]["price"] * count($pertbArr);
            if ($onetimeArr)
                $total += $onetimeArr[0]["price"];
        }

        echo $total;
    }
} elseif ($task == "get_max_ppl") {
    $proid = filter_var($_POST["proid"], FILTER_VALIDATE_INT);
    $seat = filter_var($_POST["seat"], FILTER_SANITIZE_STRING);
    $posttype = filter_var($_POST["posttype"], FILTER_SANITIZE_STRING);

    $_SESSION["seat" . $proid] = $seat;

    if ($posttype == "events") {
        $proTb = $wpdb->prefix . 'scwatbwsr_products';
        $tablesTb = $wpdb->prefix . 'scwatbwsr_tables';
        $typesTb = $wpdb->prefix . 'scwatbwsr_types';

        $getRoomSql = $wpdb->prepare("SELECT * from {$proTb} where proid=%d", $proid);
        $room = $wpdb->get_results($getRoomSql);
        $roomid = $room[0]->roomid;

        $maxppl = "";

        if (isset($_SESSION["seat" . $proid])) {
            $seat = $_SESSION["seat" . $proid];
            $pertbArr = array();
            $onetimeArr = array();

            $getTableSql = $wpdb->prepare("SELECT * from {$tablesTb} where roomid=%d and label=%s", $roomid, $seat);
            $getTable = $wpdb->get_results($getTableSql);
            $typeid = $getTable[0]->type;

            $getTypeSql = $wpdb->prepare("SELECT maxppl from {$typesTb} where id=%d ", $typeid);
            $getType = $wpdb->get_results($getTypeSql);
            $maxppl = $getType[0]->maxppl;
        }

        echo $maxppl;
    }
} elseif ($task == "delete_order") {
    $oid = filter_var($_POST["oid"], FILTER_VALIDATE_INT);

    $ordersTB = $wpdb->prefix . 'scwatbwsr_orders';
    $wpdb->query($wpdb->prepare("DELETE FROM $ordersTB where id=%d", $oid));
} elseif ($task == "make_as_booked") {
    $roomId = filter_var($_POST["roomId"], FILTER_VALIDATE_INT);
    $table = filter_var($_POST["table"], FILTER_SANITIZE_STRING);
    $proid = filter_var($_POST["proid"], FILTER_VALIDATE_INT);

    $bookedTb = $wpdb->prefix . 'scwatbwsr_bookedseats';
    $getdtSql = $wpdb->prepare("SELECT * from {$bookedTb} where roomid=%d and tb=%s and proid=%d", $roomId, $table, $proid);
    $rs = $wpdb->get_results($getdtSql);

    if ($rs) {
        $wpdb->query($wpdb->prepare("DELETE FROM $bookedTb where roomid=%d and tb=%s and proid=%d", $roomId, $table, $proid));
    } else {
        $wpdb->query($wpdb->prepare("INSERT INTO $bookedTb (roomid, tb, proid)
		VALUES (%d, %s, %d)",
            $roomId, $table, $proid));
    }
}