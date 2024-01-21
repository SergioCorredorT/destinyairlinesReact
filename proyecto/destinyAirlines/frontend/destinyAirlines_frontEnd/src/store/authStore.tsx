import { create } from "zustand";
import { removeFromNestedKeyInLocalStorage, saveToNestedKeyInLocalStorage } from "../services/localStorageUtils";

interface AuthStoreState {
  isLoggedIn: boolean;
  accessToken: string;
  refreshToken: string;
  title: string;
  firstName: string;
  lastName: string;
  setIsLoggedIn: (isLoggedIn: boolean) => void;
  setAccessToken: (accessToken: string) => void;
  setRefreshToken: (refreshToken: string) => void;
  setTitle: (title: string) => void;
  setFirstName: (firstName: string) => void;
  setLastName: (lastName: string) => void;
  reset: () =>void;
}

export const useAuthStore = create<AuthStoreState>((set) => ({
  isLoggedIn: false,
  accessToken: "",
  refreshToken: "",
  title: "",
  firstName: "",
  lastName: "",
  setIsLoggedIn: (isLoggedIn: boolean) => {
    saveToNestedKeyInLocalStorage(["isLoggedIn"], true);
    set({ isLoggedIn });
  },
  setAccessToken: (accessToken: string) => {
    saveToNestedKeyInLocalStorage(["tokens", "accessToken"], accessToken || "");
    set({ accessToken });
  },
  setRefreshToken: (refreshToken: string) => {
    saveToNestedKeyInLocalStorage(
      ["tokens", "refreshToken"],
      refreshToken || ""
    );
    set({ refreshToken });
  },
  updateTokens: () => {
    //POR HACEEEEEEEEEEEEEEEEEEEEEEEEER
  },
  setTitle: (title: string) => {
    saveToNestedKeyInLocalStorage(["userData", "title"], title || "");
    set({ title });
  },
  setFirstName: (firstName: string) => {
    saveToNestedKeyInLocalStorage(["userData", "firstName"], firstName || "");
    set({ firstName });
  },
  setLastName: (lastName: string) => {
    saveToNestedKeyInLocalStorage(["userData", "lastName"], lastName || "");
    set({ lastName });
  },
  reset: () => {
    removeFromNestedKeyInLocalStorage(["tokens"])
    removeFromNestedKeyInLocalStorage(["userData"])
    removeFromNestedKeyInLocalStorage(["isLoggedIn"])
    set({
      isLoggedIn: false,
      accessToken: "",
      refreshToken: "",
      title: "",
      firstName: "",
      lastName: "",
    });
  },
}));
