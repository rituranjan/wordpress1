function formatDateToYYYYMMDDHHMMSS(dateObj) {
    const pad = (num) => String(num).padStart(2, '0');
    let date= new Date(dateObj);
    const year = date.getFullYear();
    const month = pad(date.getMonth() + 1); // getMonth is 0-based
    const day = pad(date.getDate());
    const hours = pad(date.getHours());
    const minutes = pad(date.getMinutes());
    const seconds = pad(date.getSeconds());
  
    return `${year}${month}${day}${hours}${minutes}${seconds}`;
  }
  