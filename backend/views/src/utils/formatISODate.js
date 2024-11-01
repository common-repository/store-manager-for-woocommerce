// @ts-nocheck
function formatISODate(dateString) {
  const date = new Date(dateString);

  if (isNaN(date.getTime())) {
    return '-';
  }

  const day = date.getDate();
  const month = date.toLocaleString('default', { month: 'short' });
  const year = date.getFullYear();

  const hours = date.getHours().toString().padStart(2, '0');
  const minutes = date.getMinutes().toString().padStart(2, '0');

  return `${day} ${month}, ${year}, ${hours}:${minutes}`;
}

export default formatISODate;
