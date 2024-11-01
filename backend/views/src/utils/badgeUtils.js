// @ts-nocheck
import badgeImages from '../../assets/badge/badgeImageData.json';
const getPositionStyles = (position) => {
  switch (position) {
    case 'top-left':
      return 'top: 0; left: 0;';
    case 'top-right':
      return 'top: 0; right: 0;';
    case 'bottom-left':
      return 'bottom: 0; left: 0;';
    case 'bottom-right':
      return 'bottom: 0; right: 0;';
    case 'center':
      return 'top: 50%; left: 50%; transform: translate(-50%, -50%);';
    default:
      return '';
  }
};
export const generateBadgeHtml = (badge_settings) => {
  return `<div style='
      margin:${badge_settings.margin}px;
      color: ${badge_settings.color};
      background-color: ${badge_settings.backgroundColor};
      height: ${badge_settings.height}px;
      width: ${badge_settings.width}px;
      border-top-left-radius: ${badge_settings.borderTopLeftRadius}px;
      border-top-right-radius: ${badge_settings.borderTopRightRadius}px;
      border-bottom-left-radius: ${badge_settings.borderBottomLeftRadius}px;
      border-bottom-right-radius: ${badge_settings.borderBottomRightRadius}px;
      font-size: ${badge_settings.fontSize}px;
      font-weight: ${badge_settings.fontWeight};
      border: ${badge_settings.borderWidth}px solid ${badge_settings.borderColor};
      ${getPositionStyles(badge_settings.position)}
      position: absolute;
      text-align: center;
      z-index: 999;
    '><span style='vertical-align: middle; line-height: ${
      badge_settings.height - 2 * badge_settings.borderWidth
    }px; display: inline-block;'>
    ${badge_settings.text}
  </span></div>`;
};

export const badgeCustomSettings = {
  filterType: 'all',
  text: 'Badge Text',
  position: 'top-right',
  color: '#ffffff',
  backgroundColor: '#000000',
  height: 36,
  width: 100,
  margin: 10,
  borderTopLeftRadius: 0,
  borderTopRightRadius: 0,
  borderBottomLeftRadius: 0,
  borderBottomRightRadius: 0,
  fontSize: 14,
  fontWeight: 400,
  borderWidth: 1,
  borderColor: '#007CF5',
};

export const generateImageBadgeHtml = (badge_settings) => {
  return `<img src='${SMX.badge_image_file}${badge_settings?.image?.src}' style='
      margin:${badge_settings.margin}px;
      height: ${badge_settings.height}px;
      width: ${badge_settings.width}px;
      object-fit: contain;
      ${getPositionStyles(badge_settings.position)}
      position: absolute;
      z-index: 999;
    '></img>`;
};

export const badgeImageSettings = {
  filterType: 'all',
  position: 'top-right',
  selectedBadgeGroup: Object.keys(badgeImages.badges)[0],
  image: badgeImages.badges[Object.keys(badgeImages.badges)[0]][0],
  height: 50,
  width: 50,
  margin: 10,
};
