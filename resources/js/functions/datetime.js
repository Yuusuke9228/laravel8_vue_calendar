import {format, isWithinInterval} from 'date-fns'; // 追加
import { ja } from 'date-fns/locale'; // 追加

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

// 追加

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

// ここまで
