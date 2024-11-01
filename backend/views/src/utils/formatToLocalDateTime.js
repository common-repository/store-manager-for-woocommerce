// @ts-nocheck
const formatToLocalDateTime = (dateString) => {
  const date = new Date(dateString);

  if (isNaN(date.getTime())) {
    return '';
  }

  return date.toISOString().slice(0, 16);
};

export default formatToLocalDateTime;
