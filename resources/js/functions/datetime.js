import { format, addHours, isWithinInterval } from 'date-fns'; // addHourを追加
import { ja } from 'date-fns/locale';

/**
 * 時刻選択用のオブジェクトを生成
 *
 * @returns {FlatArray<string[][], 1>[]}
 */
export const getTimeIntervalList = () => {
    const hours = [...Array(24)].map((_, i) => ('0' + i).slice(-2));
    const minutes = ['00', '15', '30', '45'];
    return hours.map(hour => minutes.map(minute => hour + ':' + minute)).flat();
}

/**
 * startDateとendDateの間にdateが含まれるかどうか
 *
 * @param date
 * @param startDate
 * @param endDate
 */
export const isDateWithinInterval = (date, startDate, endDate) => {
    return isWithinInterval(new Date(date), { start: new Date(startDate), end: new Date(endDate) });
}

/**
 * 日付を日本語表記にする
 *
 * @param date
 * @returns {string}
 */
export const formatDateToJa = date => {
    return format(new Date(date), 'M月d日(E)', {locale: ja});
}

/**
 * 日付の比較を行う
 *
 * @param a
 * @param b
 * @returns {number}
 */
export const compareDates = (a, b) => {
    if (a.start < b.start) return -1;
    if (a.start > b.start) return 1;
    return 0;
}

// 追加

/***
 * カレンダーでクリックされた日時から、デフォルトの日時を生成
 *
 * @param date
 * @returns {[string, string]}
 */
export const getDefaultStartAndEnd = date => {
    const currentTime = format(new Date(), 'HH:mm:ss');
    const dateTime = new Date(`${date} ${currentTime}`);
    const start = format(addHours(dateTime, 0), 'yyyy/MM/dd HH:00:00');
    const end = format(addHours(dateTime, 1), 'yyyy/MM/dd HH:00:00');

    return [start, end];
}

/**
 * 終了日時が開始日時の後になっているか
 *
 * @param startDate
 * @param startTime
 * @param endDate
 * @param endTime
 * @param allDay
 * @returns {boolean}
 */
export const isGreaterEndThanStart = (startDate, startTime, endDate, endTime, allDay) => {
    if (allDay) { // 終日の場合
        const start = new Date(startDate).getTime();
        const end = new Date(endDate).getTime();

        return end >= start;
    } else {
        const start = new Date(`${startDate} ${startTime}`).getTime();
        const end = new Date(`${endDate} ${endTime}`).getTime();

        return end > start;
    }
}
// ここまで
