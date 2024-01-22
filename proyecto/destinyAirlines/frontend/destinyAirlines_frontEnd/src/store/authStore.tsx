import { create } from "zustand";
import {
  removeFromNestedKeyInLocalStorage,
  saveToNestedKeyInLocalStorage,
} from "../services/localStorageUtils";
import { customFetch } from "../services/fetchUtils";
import { toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

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
  reset: () => void;
  updateTokens: () => void;
}

const updateAccessToken = async (accessToken: string) => {
  return await customFetch(
    "http://localhost/destinyairlinesReact/proyecto/destinyAirlines/backend/MainController.php",
    { command: "checkUpdateAccessToken", accessToken: accessToken }
  );
};

const updateRefreshToken = async (refreshToken: string) => {
  return await customFetch(
    "http://localhost/destinyairlinesReact/proyecto/destinyAirlines/backend/MainController.php",
    { command: "checkUpdateRefreshToken", refreshToken: refreshToken }
  );
};

const handleError = (data: any, reset: () => void) => {
  if (data.tokenError) {
    if (data.tokenError === "expired_token") {
      reset();
      toast.info("Sesión expirada");
      return false;
    }
    toast.error("Hubo un error en la actualización de sesión");
    return false;
  }
  return true;
};

export const useAuthStore = create<AuthStoreState>((set, get) => ({
  isLoggedIn: false,
  accessToken: "",
  refreshToken: "",
  title: "",
  firstName: "",
  lastName: "",
  setIsLoggedIn: (isLoggedIn: boolean) => {
    saveToNestedKeyInLocalStorage(["isLoggedIn"], isLoggedIn);
    if (isLoggedIn) {
      toast.success("Iniciada sesión");
    } else {
      toast.warn("Sesión cerrada");
    }
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
    const {
      accessToken,
      refreshToken,
      setAccessToken,
      setRefreshToken,
      reset,
    } = get();
    updateAccessToken(accessToken).then((data) => {
      if (data.accessToken) {
        setAccessToken(data.accessToken);
      } else if (data.tokenError && data.tokenError == "expired_token") {
        updateRefreshToken(refreshToken).then((data) => {
          if (!handleError(data, reset)) {
            return;
          }
          setAccessToken(data.accessToken);
          if (data.refreshToken) {
            setRefreshToken(data.refreshToken);
          }
        });
      }
    });
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
    removeFromNestedKeyInLocalStorage(["tokens"]);
    removeFromNestedKeyInLocalStorage(["userData"]);
    removeFromNestedKeyInLocalStorage(["isLoggedIn"]);
    set({
      isLoggedIn: false,
      accessToken: "",
      refreshToken: "",
      title: "",
      firstName: "",
      lastName: "",
    });
    toast.warn("Sesión cerrada");
  },
}));
