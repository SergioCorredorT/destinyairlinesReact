import { create } from "zustand";
import {
  removeFromNestedKeyInLocalStorage,
  saveToNestedKeyInLocalStorage,
} from "../services/localStorageUtils";
import { destinyAirlinesFetch } from "../services/fetchUtils";
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
  signIn: ({
    emailAddress,
    password,
  }: {
    emailAddress: string;
    password: string;
  }) => Promise<{ status: boolean; message: string }>;
  signOut: () => void;
  updateTokens: () => void;
}

const updateAccessToken = async (accessToken: string) => {
  return await destinyAirlinesFetch(
    { command: "checkUpdateAccessToken", accessToken: accessToken }
  );
};

const updateRefreshToken = async (refreshToken: string) => {
  return await destinyAirlinesFetch(
    { command: "checkUpdateRefreshToken", refreshToken: refreshToken }
  );
};

const handleError =  ({ error, signOut }: { error: string; signOut: () => void; }) => {
    if (error === "expired_token") {
      signOut();
      toast.info("Sesión expirada");
    }
    toast.error("Hubo un error en la actualización de sesión");
};

export const useAuthStore = create<AuthStoreState>((set, get) => ({
  isLoggedIn: false,
  accessToken: "",
  refreshToken: "",
  title: "",
  firstName: "",
  lastName: "",
  setIsLoggedIn: (isLoggedIn) => {
    saveToNestedKeyInLocalStorage(["isLoggedIn"], isLoggedIn);
    set({ isLoggedIn });
  },
  setAccessToken: (accessToken) => {
    saveToNestedKeyInLocalStorage(["tokens", "accessToken"], accessToken || "");
    set({ accessToken });
  },
  setRefreshToken: (refreshToken) => {
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
      signOut,
    } = get();
    updateAccessToken(accessToken).then((data) => {
      if (data.accessToken) {
        setAccessToken(data.accessToken);
      } else if (data.tokenError && data.tokenError == "expired_token") {
        updateRefreshToken(refreshToken).then((data) => {
          if (data.tokenError) {
            handleError({error : data.tokenError, signOut})
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
  setTitle: (title) => {
    saveToNestedKeyInLocalStorage(["userData", "title"], title || "");
    set({ title });
  },
  setFirstName: (firstName) => {
    saveToNestedKeyInLocalStorage(["userData", "firstName"], firstName || "");
    set({ firstName });
  },
  setLastName: (lastName) => {
    saveToNestedKeyInLocalStorage(["userData", "lastName"], lastName || "");
    set({ lastName });
  },
  signIn: async ({ emailAddress, password }) => {
    const response = await destinyAirlinesFetch(
      { emailAddress, password, command: "loginUser" }
    );
    const {
      setAccessToken,
      setRefreshToken,
      setTitle,
      setFirstName,
      setLastName,
      setIsLoggedIn,
    } = get();

    if (response.error) {
      toast.error(`Error en la petición a servidor: ${response.error}`);
      return { status: false, message: "Error de servidor" };
    }

    if (response && response.status && response.response) {
      setAccessToken(response.tokens.accessToken);
      setRefreshToken(response.tokens.refreshToken);
      setTitle(response.response.userData.title);
      setFirstName(response.response.userData.firstName);
      setLastName(response.response.userData.lastName);
      setIsLoggedIn(true);
      toast.success("Se ha iniciado sesión");
      return { status: true, message: "Se ha iniciado sesión" };
    } else {
      toast.error(
        "Correo electrónico o contraseña incorrectos. Tras 5 intentos fallidos, tu cuenta se bloqueará y recibirás un correo de recuperación"
      );
      return { status: false, message: "La autenticación ha fallado" };
    }
  },
  signOut: () => {
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
