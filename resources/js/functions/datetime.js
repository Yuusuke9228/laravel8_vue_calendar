export const getTimeIntervalList = () => {
    const hours = [...Array(24)].map((_, i) => ('0' + i).slice(-2));
    const minutes = ['00', '15', '30', '45'];
    return hours.map(hour => minutes.map(minute => hour + ':' + minute)).flat();
}
