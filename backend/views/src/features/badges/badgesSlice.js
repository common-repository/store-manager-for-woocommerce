// @ts-nocheck
import { createSlice } from '@reduxjs/toolkit';

const initialState = {
  badge_name: '',
  badge_type: 'custom',
  valid_from: '',
  valid_to: '',
  badge_settings: {},
  filter: 'all',
  badge_style: '',
};

const badgesSlice = createSlice({
  name: 'products',
  initialState,
  reducers: {
    changeBadgeBaseProperties: (state, action) => {
      state[action.payload.name] = action.payload.value;
    },

    changeBadgeSettingProperties: (state, action) => {
      state.badge_settings[action.payload.name] = action.payload.value;
    },
    setCompleteBadgeState: (state, action) => {
      return action.payload;
    },
    resetBadgeState: () => {
      return initialState;
    },
  },
});

export const { changeBadgeBaseProperties, changeBadgeSettingProperties, setCompleteBadgeState, resetBadgeState } =
  badgesSlice.actions;
export default badgesSlice.reducer;
