// @ts-nocheck
const booleanConverter = (value) => {
  const falsyValues = [false, 'false', 0, '0', '', null, undefined];

  if (Number.isNaN(value)) {
    return false;
  }

  return !falsyValues.includes(value);
};

export default booleanConverter;
