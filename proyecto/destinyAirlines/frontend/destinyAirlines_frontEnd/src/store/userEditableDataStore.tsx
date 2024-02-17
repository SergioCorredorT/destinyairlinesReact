import { create } from 'zustand';

// Define el tipo para el estado de tu store
type State = {
  userEditableInfo: any;
  setUserEditableInfo: (info: any) => void;
};

// Crear el store
export const userEditableDataStore = create<State>((set) => ({
  userEditableInfo: {},
  setUserEditableInfo: (info) => set(state => ({ userEditableInfo: info })),
}));
