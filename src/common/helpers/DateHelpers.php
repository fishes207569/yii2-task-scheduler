<?php

namespace ccheng\task\common\helpers;

use DateInterval;
use DateTime;
use yii\base\Exception;
use yii\base\UserException;

/**
 * 专门计算时间相关的Helper函数
 * Dh 代表 Date Helper，为写代码方便，用了缩写，因为用得多，所以短一点
 *
 * @author charles
 */
class DateHelpers
{

    const USE_UNIXTIMESTAMP = true;
    const PERIOD_MINUTIE    = 60;
    const PERIOD_HOUR       = 3600;
    const PERIOD_1DAY       = 86400; //60 * 60 * 24 = 86400
    const PERIOD_30DAYS     = 2592000; //60 * 60 * 24 * 30 = 2592000
    const DATE_OPERATOR_ADD = 'add';
    const DATE_OPERATOR_SUB = 'sub';
    const DATE_START_DATE   = '1970-01-01';

    /**
     * 计算本月的月初
     *
     * @param boolean $unix_timestamp
     *
     * @return mixed
     */
    public static function thisMonthStart($unix_timestamp = self::USE_UNIXTIMESTAMP)
    {
        $timestamp = strtotime(date('Y-m-01 00:00:00', time()));

        return $unix_timestamp ? $timestamp : date('Y-m-d 00:00:00', $timestamp);
    }

    public static function thisMonthEnd($unix_timestamp = self::USE_UNIXTIMESTAMP)
    {
        $timestamp = strtotime(date('Y-m-01 00:00:00', time()) . ' +1 month');

        return $unix_timestamp ? $timestamp : date('Y-m-d 00:00:00', $timestamp);
    }

    public static function calcLastMonthStart($timestamp, $unix_timestamp = self::USE_UNIXTIMESTAMP)
    {
        $lastMonthTime = strtotime(date('Y-m-01 00:00:00', $timestamp) . ' -1 month');

        return $unix_timestamp ? $lastMonthTime : date('Y-m-01 00:00:00', $lastMonthTime);
    }

    public static function calcNextMonthStart($timestamp, $unix_timestamp = self::USE_UNIXTIMESTAMP)
    {
        $nextMonthTime = strtotime(date('Y-m-01 00:00:00', $timestamp) . ' +1 month');

        return $unix_timestamp ? $nextMonthTime : date('Y-m-01 00:00:00', $nextMonthTime);
    }

    public static function calcNextMonthEnd($timestamp, $unix_timestamp = self::USE_UNIXTIMESTAMP)
    {
        $nextMonthTime = strtotime(date('Y-m-01 00:00:00', $timestamp) . ' +2 month');

        return $unix_timestamp ? $nextMonthTime : date('Y-m-01 00:00:00', $nextMonthTime);
    }

    /**
     * 计算给定时间戳的月初始
     *
     * @param int     $timestamp
     * @param boolean $unix_timestamp
     *
     * @return mixed
     */
    public static function calcMonthStart($timestamp, $unix_timestamp = self::USE_UNIXTIMESTAMP)
    {
        $time = date('Y-m-01 00:00:00', $timestamp);

        return ($unix_timestamp) ? strtotime($time) : $time;
    }

    public static function calcMonthEnd($timestamp, $unix_timestamp = self::USE_UNIXTIMESTAMP)
    {
        return self::calcNextMonthStart($timestamp, $unix_timestamp);
    }

    public static function formatDate($dateTime)
    {
        return date("Y-m-d", strtotime($dateTime));
    }

    public static function todayDate($withSpliter = true)
    {
        return $withSpliter ? date('Y-m-d') : date('Ymd');
    }

    public static function now($format = 'Y-m-d H:i:s')
    {
        if (false === $format) {
            return time();
        } else {
            return date($format);
        }
    }

    public static function getcurrentDateTime($intervalMinutes = 0)
    {
        $time     = new DateTime();
        $interval = new DateInterval('PT' . abs($intervalMinutes) . 'M');
        if ($intervalMinutes >= 0) {
            $time->add($interval);
        } else {
            $time->sub($interval);
        }

        return $time->format('Y-m-d H:i:s');
    }

    /**
     * 时间增加或减少分钟
     *
     * @param string $dateTime
     * @param int    $intervalMinutes
     *
     * @return string
     */
    public static function calcDateTimeFromAddMinutes($dateTime, $intervalMinutes = 0)
    {
        $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $dateTime);
        $interval = new DateInterval('PT' . abs($intervalMinutes) . 'M');
        if ($intervalMinutes >= 0) {
            $dateTime->add($interval);
        } else {
            $dateTime->sub($interval);
        }

        return $dateTime->format('Y-m-d H:i:s');
    }

    public static function calcSpecialTodayDateTime($time)
    {
        return DateTime::createFromFormat('Y-m-d H:i:s', self::todayDate() . ' ' . $time)->format('Y-m-d H:i:s');
    }

    public static function calcDateTime($datetime, $intervalSeconds)
    {
        $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
        $interval = new DateInterval('PT' . abs($intervalSeconds) . 'S');
        if ($intervalSeconds >= 0) {
            $datetime->add($interval);
        } else {
            $datetime->sub($interval);
        }

        return $datetime->format('Y-m-d H:i:s');
    }

    public static function tomorrowDate()
    {
        return date('Y-m-d', self::getTomorrowStart());
    }

    public static function getTomorrowStart($unix_timestamp = self::USE_UNIXTIMESTAMP)
    {
        $time = strtotime('tomorrow');

        return $unix_timestamp ? $time : date('Y-m-d H:i:s', $time);
    }

    public static function getTodayStart($unix_timestamp = self::USE_UNIXTIMESTAMP)
    {
        $time = strtotime('today');

        return $unix_timestamp ? $time : date('Y-m-d H:i:s', $time);
    }

    public static function yesterdayDate()
    {
        return date('Y-m-d', self::getYesterdayStart());
    }

    public static function getYesterdayStart($unix_timestamp = self::USE_UNIXTIMESTAMP)
    {
        $time = strtotime('yesterday');

        return $unix_timestamp ? $time : date('Y-m-d H:i:s', $time);
    }

    public static function checkDate($date)
    {
        return preg_match('/[12]\d{3}-[01]\d-[0-3]\d/', $date, $matches) !== 0;
        /*if (preg_match('/[12]\d{3}-[01]\d-[0-3]\d/', $date, $matches) === false) {
            return false;
        }
        $d = DateTime::createFromFormat('Y-m-d', $matches[0]);

        return $d && $d->format('Y-m-d') == $matches[0];*/
    }

    public static function newCheckDate($date)
    {
        return preg_match('/[12]\d{3}-[01]\d-[0-3]\d/', $date, $matches) !== 0;
    }

    public static function validDate($date)
    {
        if (!isset($date)) {
            return false;
        }

        $date = self::formatDate($date);

        if ($date == "1970-01-01" || $date <= '2000-01-01') {
            return false;
        }

        return true;
    }

    /**
     * 获取开始时间
     *
     * @param string $date           日期，格式如 20141225 或者 2014-12-25
     * @param bool   $unix_timestamp Unix时间戳
     *
     * @return false|int|string
     */
    public static function getDateStart($date, $unix_timestamp = self::USE_UNIXTIMESTAMP)
    {
        $datetime = date('Y-m-d 00:00:00', strtotime($date));

        return $unix_timestamp ? strtotime($datetime) : $datetime;
    }

    /**
     * 获取结束时间
     *
     * @param string $date           日期，格式如 20141225 或者 2014-12-25
     * @param bool   $unix_timestamp Unix时间戳
     *
     * @return false|int|string
     */
    public static function getDateEnd($date, $unix_timestamp = self::USE_UNIXTIMESTAMP)
    {
        $datetime           = date('Y-m-d 00:00:00', strtotime($date));
        $end_datetime_stamp = strtotime($datetime . ' +1 days');

        return $unix_timestamp ? $end_datetime_stamp : date('Y-m-d H:i:s', $end_datetime_stamp);
    }

    /*
     * @param $date 格式为：2016－01-01或20160101，$offsetime string 时分秒偏移量
     * 功能：给日期加时分秒便宜量，比如使日期统一为2016-01-01 23:59:59
     * 举例：calDayHmsOffset('2016-01-01','23:59:59')
     */
    public static function calDayHmsOffset($date, $offsetTime)
    {
        if (!isset($date) || empty($date)) {
            return;
        } else {
            $datetime = date('Y-m-d ' . $offsetTime, strtotime($date));

            return $datetime;
        }
    }

    /**
     * 计算两个日期之前的天数，前面一个参数要比后面一个大，否则返回负数
     *
     * @param string $big   日期字符串，如2015-01-02
     * @param string $small 日期字符串，如2015-01-02
     *
     * @return int 天数的差，可能是负数
     */
    public static function calcDays($big, $small)
    {
        $minus = false;
        $time1 = strtotime($big);
        $time2 = strtotime($small);
        if ($time1 < $time2) {
            $minus = true;
        }
        $interval = date_diff(
            date_create(date('Y-m-d', strtotime($big))),
            date_create(date('Y-m-d', strtotime($small)))
        );

        return $minus ? 0 - $interval->format('%a') : intval($interval->format('%a'));
    }

    /**
     * 生成时间闭区间
     *
     * @param string $timeStr
     *
     * @return array
     */
    public static function calcMonthBetween($timeStr)
    {
        if (strlen($timeStr) == 6) {
            // 201409 like string expected.
            $timestamp = strtotime($timeStr . '01');
        } else {
            if (is_string($timeStr)) {
                $timestamp = strtotime($timeStr);
            } else {
                $timestamp = $timeStr;
            }
        }
        $start = self::calcMonthStart($timestamp, false);
        $end   = date('Y-m-d H:i:s', self::calcMonthEnd($timestamp) - 1);

        return [$start, $end];
    }

    /**
     * 两天减要加一天
     *
     * @param $beginDate
     * @param $endDate
     *
     * @return int
     */
    public static function getDaysByInterestDate($beginDate, $endDate)
    {
        return intval((strtotime($endDate) - strtotime($beginDate)) / 86400) + 1;
    }

    /**
     * 给定时间计算返回传入日期的加减年月日
     *
     * @param string  $beginDate 开始时间
     * @param integer $addDate   累加时间
     * @param string  $dateType  时间类型 Y为年,M为月,D为日
     * @param string  $operator  'add' 为加 'sub'为减
     *
     * @return Datetime|string
     */
    public static function calcDateFromAddDate($beginDate, $addDate, $dateType = 'M', $operator = self::DATE_OPERATOR_ADD, $returnDateType = 'Y-m-d')
    {
        $dateType = strtoupper($dateType);
        if (in_array($dateType, ['Y', 'M', 'D'])) {
            $type = 'P';
        } else {
            $type = 'PT';
        }
        if ($operator === self::DATE_OPERATOR_ADD) {
            $date = date_create($beginDate)
                ->add(new DateInterval("{$type}{$addDate}{$dateType}"))
                ->format($returnDateType);
        } else {
            $date = date_create($beginDate)
                ->sub(new DateInterval("{$type}{$addDate}{$dateType}"))
                ->format($returnDateType);
        }

        return $date;
    }

    /**
     * 给定时间计算返回 Y-m-d
     *
     * @param string $date           开始时间
     * @param string $returnDateType 时间类型
     *
     * @return Datetime
     */
    public static function getAppointDate($date, $returnDateType = 'Y-m-d')
    {
        return date_create($date)->format($returnDateType);
    }

    /**
     * 返回还款时间：31号还款遇到当月没有31号则使用月末日期
     * 算法：如果还款当月同日不等于还款下月同日， 还款日期等于下个月月末
     *
     * @param DateTime $grantTime 放款日期
     * @param integer  $period    期次 按月
     *
     * @return DateTime $date 下个月还款日期
     */
    public static function getNextRepayDate($grantTime, $period)
    {
        $nowDate     = date_create($grantTime);
        $nextDate    = date_create($grantTime)->add(new DateInterval("P{$period}M"));
        $nowDateDay  = $nowDate->format('d');
        $nextDateDay = $nextDate->format('d');

        if ($nowDateDay == $nextDateDay) {
            $returnDate = $nextDate->format('Y-m-d');
        } else {
            $nextDateFirstDay        = $nextDate->format('Y-m-1');
            $nextDateFirstDayDate    = new DateTime($nextDateFirstDay);
            $nextDatePreMonthLastDay = $nextDateFirstDayDate->sub(new DateInterval('P1D'));
            $returnDate              = $nextDatePreMonthLastDay->format('Y-m-d');
        }

        return $returnDate;
    }

    /**
     * 计算两个日期的天数差
     */
    public static function diffBetweenTwoDays($beginDate, $endDate)
    {
        $day1 = date_create(date("Y-m-d", strtotime($beginDate)));
        $day2 = date_create(date("Y-m-d", strtotime($endDate)));

        return abs(date_diff($day1, $day2)->days);
    }

    public static function getMonthEndDay($date)
    {
        return date('Y-m-d', strtotime(date('Y-m-01', strtotime($date)) . ' +1 month -1 day'));
    }

    /**
     * 两个日期间的月份，不足一个月算一月
     *
     * @param $startDate
     * @param $endDate
     *
     * @return mixed
     */
    public static function getMonthNum($startDate, $endDate)
    {
        $startDdateStamp = strtotime($startDate);
        $endDateStamp    = strtotime($endDate);
        list($startTime['y'], $startTime['m'], $startTime['d']) = explode("-", date('Y-m-d', $startDdateStamp));
        list($endTime['y'], $endTime['m'], $endTime['d']) = explode("-", date('Y-m-d', $endDateStamp));
        $intervalMonth = ($endTime['y'] - $startTime['y'] - 1) * 12 + (+$endTime['m'] - $startTime['m'] + 12);
        if ($endTime['d'] >= $startTime['d'] || self::getMonthEndDay($endDate) == self::getAppointDate($endDate)) {
            $intervalMonth++;
        }

        return $intervalMonth;
    }

    /**
     * 获取当前时间(小时:分)
     *
     * @param int $intervalMinutes
     *
     * @return string
     */
    public static function getCurrentTime($intervalMinutes = 0)
    {
        $time     = new DateTime();
        $interval = new DateInterval('PT' . abs($intervalMinutes) . 'M');
        if ($intervalMinutes >= 0) {
            $time->add($interval);
        } else {
            $time->sub($interval);
        }

        return $time->format('H:i');
    }

    /**
     * 计算两个时间的小时差
     *
     * @param $startDate
     * @param $endDate
     *
     * @return float
     * @throws Exception
     */
    public static function calcHours($startDate, $endDate)
    {
        if (!(self::checkDate($startDate) && self::checkDate($endDate))) {
            throw new Exception('输入时间不正确');
        }
        $startDate = strtotime($startDate);
        $endDate   = strtotime($endDate);

        return (int)round(($startDate - $endDate) / 3600, 0);
    }

    public static function calcDiffDays($startDate, $endDate)
    {
        return (new DateTime(date('Y-m-d', strtotime($startDate))))
            ->diff(new DateTime(date('Y-m-d', strtotime($endDate))))
            ->format('%a');
    }

    /**
     * 计算两个时间的分钟差
     *
     * @param $startDate
     * @param $endDate
     *
     * @return string
     */
    public static function calcMinutes($startDate, $endDate)
    {
        return intval((strtotime($endDate) - strtotime($startDate)) % self::PERIOD_1DAY / self::PERIOD_MINUTIE);
    }

    /**
     * 计算两个时间的秒差
     *
     * @param $startDate
     * @param $endDate
     *
     * @return int
     */
    public static function calcSeconds($startDate, $endDate)
    {
        return intval(abs((strtotime($endDate) - strtotime($startDate)) / 3600 * 60 * 60));
    }

    /**
     * 比较两个时间的大小
     *
     * @param string $leftOperand
     * @param string $rightOperand
     *
     * @return bool 返回true，说明左边时间比右边的大，否则，反之
     * @throws \yii\base\Exception
     */
    public static function compareDateTime($leftOperand, $rightOperand)
    {
        if (!(self::checkDate($leftOperand) && self::checkDate($rightOperand))) {
            throw new Exception('输入时间不正确');
        }
        $leftOperand  = strtotime($leftOperand);
        $rightOperand = strtotime($rightOperand);

        return $leftOperand - $rightOperand > 0;
    }

    /**
     * 时间类型转换
     *
     * @param $dateTime
     * @param $inputStyle
     * @param $outStyle
     *
     * @return string
     * @throws \yii\base\Exception
     */
    public static function changeStyle($dateTime, $inputStyle, $outStyle)
    {
        $dateTimeToFormat = DateTime::createFromFormat($inputStyle, $dateTime);

        if (false !== $dateTimeToFormat) {
            return DateTime::createFromFormat($inputStyle, $dateTime)->format($outStyle);
        } else {
            throw new Exception('时间格式不正确');
        }
    }

    /**
     * 计算星期, 0为星期日
     *
     * @param $dateTime
     *
     * @return false|string
     */
    public static function calcWeekDay($dateTime)
    {
        return date('w', strtotime($dateTime));
    }

    /**
     * 获取下一个月，如果跨月，取最后一天
     *
     * @param $date
     * @param $month
     *
     * @return bool|\DateTime|string
     */
    public static function addMonth($date, $month)
    {
        $arr      = explode('-', $date);
        $nextDate = Dh::calcDateFromAddDate($date, $month);
        $nextArr  = explode('-', $nextDate);
        //跨年与否
        if ($nextArr[0] != $arr[0]) {
            if ($nextArr[1] + 12 * ($nextArr[0] - $arr[0]) - $arr[1] > $month) {
                //获取到当月的第一天
                $firstDay = date('Y-m-01', strtotime("$date + $month month"));

                //再减去一天
                return date('Y-m-d', strtotime("$firstDay -1 day"));
            }
        } elseif ($nextArr[1] - $arr[1] > $month) {
            //获取到当月的第一天
            $firstDay = date('Y-m-01', strtotime("$date + $month month"));

            //再减去一天
            return date('Y-m-d', strtotime("$firstDay -1 day"));
        }

        return $nextDate;
    }

    public static function addDays($date, $days)
    {
        return self::calcDateFromAddDate($date, $days, 'D', self::DATE_OPERATOR_ADD, 'Y-m-d');
    }

    /**
     * 指定日期减去指定天数
     *
     * @param $date
     * @param $days
     *
     * @return DateTime|string
     */
    public static function subDays($date, $days)
    {
        return self::calcDateFromAddDate($date, $days, 'D', self::DATE_OPERATOR_SUB, 'Y-m-d');
    }


    /**
     * 以结束时间为基数向前推,生成固定间隔的时间段,此处不考虑开始时间边界
     *
     * @param string $startTime 开始时间
     * @param string $endTime   结束时间
     * @param int    $interval  时间间隔,一般是数据聚合时间,单位'秒'
     */
    public static function splitPeriodArray($startTime, $endTime, $interval)
    {
        $splitedScope = []; // 生成区间列表
        $startTimeInt = is_string($startTime) ? strtotime($startTime) : $startTime;
        $endTimeInt   = is_string($endTime) ? strtotime($endTime) : $endTime;

        $startConfineScope = [];

        for ($i = $endTimeInt; $i > $startTimeInt; $i = $i - $interval) {
            $endScope   = $i;
            $startScope = $i - $interval;
            if ($startScope < $startTimeInt) {
                // 开始边界已经不足一个interval,单独存储
                $startConfineScope = [date('Y-m-d H:i:s', $startTimeInt), date('Y-m-d H:i:s', $endScope)];
                break;
            }
            array_unshift($splitedScope, [date('Y-m-d H:i:s', $startScope), date('Y-m-d H:i:s', $endScope)]);
        }

        return [$splitedScope, $startConfineScope];
    }


    /**
     * 获取日
     *
     * @param $date
     *
     * @return string
     */
    public static function getDay($date)
    {
        $date_time = date_create($date);

        return $date_time->format('d');
    }

    /**
     * 获取当月某一天
     *
     * @param string $date
     * @param string $day
     *
     * @return string
     */
    public static function getMonthDay($date, $day)
    {
        $date_time = date_create($date);

        return $date_time->format("Y-m-$day 00:00:00");
    }

    /**
     * 获取下周一
     *
     * @param $date
     *
     * @return false|string
     */
    public static function getNextMonday($date)
    {
        return date('Y-m-d 00:00:00', strtotime('+1 week last monday', strtotime($date)));
    }


    /**
     * 为指定时间添加$hours个小时，时间格式：YYYY-mm-dd HH:ii:ss
     *
     * @param $datetime
     * @param $hours
     *
     * @return false|string
     */
    public static function addHours($datetime, $hours)
    {
        return date('Y-m-d H:i:s', strtotime("{$datetime} +{$hours}"));
    }

    /**
     * 为指定时间添加$minutes个小时，时间格式：YYYY-mm-dd HH:ii:ss
     *
     * @param $datetime
     * @param $minutes
     *
     * @return false|string
     */
    public static function addMinutes($datetime, $minutes)
    {
        return date('Y-m-d H:i:s', strtotime("{$datetime} +{$minutes} min"));
    }

    /**
     * 获取毫秒级时间戳
     *
     * @return float
     */
    public static function getTimestampForMillisecond()
    {
        list($t1, $t2) = explode(' ', microtime());

        return (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
    }

    /**
     * 获取中文星期
     */
    public static function getChineseWeek($date)
    {
        $weekArray = ["日", "一", "二", "三", "四", "五", "六"];

        return $weekArray[date("w", strtotime($date))];
    }

    /**
     * 获取 两天内的区间 使用 foreach 获取
     *
     * @param $startDate
     * @param $endDate
     *
     * @return \Generator
     * @throws \yii\base\UserException
     */
    public static function walkIntervalDay($startDate, $endDate)
    {
        $startDate = self::formatDate($startDate);
        $endDate   = self::formatDate($endDate);

        if ($startDate >= $endDate) {
            throw new UserException("开始时间 与 结算时间不合法");
        }

        while ($startDate <= $endDate) {
            yield $startDate;

            $startDate = self::addDays($startDate, 1);
        }
    }

    /**
     * 获取当前日期所在月份的总天数
     *
     * @param string $date
     *
     * @return false|string
     */
    public static function getCurrMonthDays($date = '')
    {
        if (empty($date)) {
            $date = date('Y-m-d H:i:s');
        } else {
            $date = date($date);
        }

        return date('t', strtotime($date));
    }

    /**
     * 判断两个日期是否在同一个月
     *
     * @param $dateStart
     * @param $dateEnd
     *
     * @return bool
     */
    public static function isSameMonth($dateStart, $dateEnd)
    {
        if (self::calcMonthStart(self::getDateStart($dateStart))
            == self::calcMonthStart(self::getDateStart($dateEnd))
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取指定日期的结束时间
     *
     * @param string $date           日期，格式如 20141225 或者 2014-12-25
     * @param bool   $unix_timestamp 是否返回Unix时间戳
     *
     * @return false|int|string
     */
    public static function getDayEnd($date, $unix_timestamp = false)
    {
        $datetime           = date('Y-m-d 00:00:00', strtotime($date));
        $end_datetime_stamp = strtotime($datetime . ' +1 days')-1;

        return $unix_timestamp ? $end_datetime_stamp : date('Y-m-d H:i:s', $end_datetime_stamp);
    }
}
