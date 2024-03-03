import { create } from "zustand";
import {
  getToNestedKeyInLocalStorage,
  saveToNestedKeyInLocalStorage,
} from "../tools/localStorageUtils";
import { destinyAirlinesFetch } from "../services/fetchUtils";
import { toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import { signIn } from "../services/signIn";
import { signOut } from "../services/signOut";
import { getUserEditableInfo } from "../services/getUserEditableInfo";
import { getUpdateTime } from "../services/getUpdateTime";
import { setDateInterval } from "../tools/timeUtils";
import { googleSignIn } from "../services/googleSignIn";

interface AuthStoreState {
  isLoggedIn: boolean;
  updateTime: number;
  updateTimeRef: any;
  accessToken: string;
  refreshToken: string;
  title: string;
  firstName: string;
  lastName: string;
  emailAddress: string;
  isSaveSecondaryUserData: boolean;
  country: string;
  townCity: string;
  streetAddress: string;
  zipCode: string;
  phoneNumber1: string;
  phoneNumber2: string;
  phoneNumber3: string;
  companyName: string;
  companyTaxNumber: string;
  companyPhoneNumber: string;
  documentationType: string;
  documentCode: string;
  expirationDate: string;
  dateBirth: string;
  activateAutoUpdateToken: () => Promise<void>;
  desactivateAutoUpdateToken: () => Promise<void>;
  getUpdateTime: () => Promise<number>;
  getUserEditableInfo: (
    forceFetch?: boolean
  ) => Promise<{ [key: string]: string | undefined | null } | null>;
  setUserEditableInfo: (newUserInfo: { [key: string]: string }) => void;
  setIsLoggedIn: (isLoggedIn: boolean) => void;
  setAccessToken: (accessToken: string) => void;
  setRefreshToken: (refreshToken: string) => void;
  setTitle: (title: string) => void;
  setFirstName: (firstName: string) => void;
  setLastName: (lastName: string) => void;
  setEmailAddress: (emailAddress: string) => void;
  setCountry: (country: string) => void;
  setTownCity: (townCity: string) => void;
  setStreetAddress: (streetAddress: string) => void;
  setZipCode: (zipCode: string) => void;
  setPhoneNumber1: (phoneNumber1: string) => void;
  setPhoneNumber2: (phoneNumber2: string) => void;
  setPhoneNumber3: (phoneNumber3: string) => void;
  setCompanyName: (companyName: string) => void;
  setCompanyTaxNumber: (companyTaxNumber: string) => void;
  setCompanyPhoneNumber: (companyPhoneNumber: string) => void;
  setDocumentationType: (documentationType: string) => void;
  setDocumentCode: (documentCode: string) => void;
  setExpirationDate: (expirationDate: string) => void;
  setDateBirth: (dateBirth: string) => void;
  signInStore: ({
    emailAddress,
    password,
  }: {
    emailAddress: string;
    password: string;
  }) => Promise<{ status: boolean; message: string }>;
  googleSignInStore: ({
    credential,
  }: {
    credential: string;
  }) => Promise<{ status: boolean; message: string }>;
  signOut: () => void;
  updateTokens: () => Promise<{ error: string | null }>;
  checkUpdateLogin: () => Promise<boolean>;
}

const updateAccessToken = async (accessToken: string) => {
  return await destinyAirlinesFetch({
    command: "checkUpdateAccessToken",
    accessToken: accessToken,
  });
};

const updateRefreshToken = async (refreshToken: string) => {
  return await destinyAirlinesFetch({
    command: "checkUpdateRefreshToken",
    refreshToken: refreshToken,
  });
};

const handleError = ({
  error,
  signOut,
}: {
  error: string;
  signOut: () => void;
}) => {
  if (error === "expired_token") {
    signOut();
    toast.info("Sesión expirada");
    return "expired_token";
  }
  toast.error("Hubo un error en la actualización de sesión");
  return "generic_error";
};

export const authStore = create<AuthStoreState>((set, get) => ({
  isLoggedIn: false,
  updateTime: 30 * 60,
  updateTimeRef: null,
  accessToken: "",
  refreshToken: "",
  title: "",
  firstName: "",
  lastName: "",
  isSaveSecondaryUserData: false,
  country: "",
  townCity: "",
  streetAddress: "",
  zipCode: "",
  phoneNumber1: "",
  phoneNumber2: "",
  phoneNumber3: "",
  companyName: "",
  companyTaxNumber: "",
  companyPhoneNumber: "",
  documentationType: "",
  documentCode: "",
  expirationDate: "",
  dateBirth: "",
  emailAddress: "",
  activateAutoUpdateToken: async () => {
    const state = get();
    let updateTimeRef = state["updateTimeRef"];
    if (updateTimeRef) {
      updateTimeRef();
    }
    const secondsUpdateTime = await state["getUpdateTime"]();
    const checkUpdateLogin = state["checkUpdateLogin"];
    updateTimeRef = setDateInterval(() => {
      checkUpdateLogin();
    }, secondsUpdateTime * 1000);
    set({ updateTimeRef });
  },
  desactivateAutoUpdateToken: async () => {
    const updateTimeRef = get()["updateTimeRef"];
    if (updateTimeRef) {
      updateTimeRef();
    }
    set({ updateTimeRef: null });
  },
  getUpdateTime: async () => {
    if (getToNestedKeyInLocalStorage(["updateTime"])) {
      return parseInt(getToNestedKeyInLocalStorage(["updateTime"]));
    }
    const response = await getUpdateTime();
    if (!response || !response.status) {
      return get()["updateTime"];
    }
    let updateTime = parseInt(response.response);
    saveToNestedKeyInLocalStorage(["updateTime"], updateTime);
    return updateTime;
  },
  setUserEditableInfo: (newUserInfo) => {
    const state = get();
    for (const key in newUserInfo) {
      const value = newUserInfo[key];
      switch (key) {
        case "title":
          state.setTitle(value || "");
          break;
        case "firstName":
          state.setFirstName(value || "");
          break;
        case "lastName":
          state.setLastName(value || "");
          break;
        case "country":
          state.setCountry(value || "");
          break;
        case "townCity":
          state.setTownCity(value || "");
          break;
        case "streetAddress":
          state.setStreetAddress(value || "");
          break;
        case "zipCode":
          state.setZipCode(value || "");
          break;
        case "phoneNumber1":
          state.setPhoneNumber1(value || "");
          break;
        case "phoneNumber2":
          state.setPhoneNumber2(value || "");
          break;
        case "phoneNumber3":
          state.setPhoneNumber3(value || "");
          break;
        case "companyName":
          state.setCompanyName(value || "");
          break;
        case "companyTaxNumber":
          state.setCompanyTaxNumber(value || "");
          break;
        case "companyPhoneNumber":
          state.setCompanyPhoneNumber(value || "");
          break;
        case "documentationType":
          state.setDocumentationType(value || "");
          break;
        case "documentCode":
          state.setDocumentCode(value || "");
          break;
        case "expirationDate":
          state.setExpirationDate(value || "");
          break;
        case "dateBirth":
          state.setDateBirth(value || "");
          break;
        default:
          console.warn(`No setter found for key "${key}"`);
      }
    }
    set({ ["isSaveSecondaryUserData"]: true });
  },
  getUserEditableInfo: async (forceFetch = false) => {
    const state = get();
    if (!state["isLoggedIn"]) {
      return { error: "El usuario no está logueado" };
    }
    //Comprobando si no tenemos la info de usuario editable en el store o si el forceFetch está activado (se usará tras el update de datos)
    if (!state["isSaveSecondaryUserData"] || forceFetch) {
      //El emailAddress siempre se carga por estar también en el localstorage
      const emailAddress = state["emailAddress"];
      const accessToken = state["accessToken"];
      const userInfo = await getUserEditableInfo({
        emailAddress,
        accessToken,
      });
      if (!userInfo.status) {
        return { error: userInfo.error };
      }
      //rellenar store con el fetch
      for (const info in userInfo.response) {
        if (!userInfo.response[info]) {
          userInfo.response[info] = "";
        }
        set({ [info]: userInfo.response[info] || "" });
      }
      set({ ["isSaveSecondaryUserData"]: true });
    }

    return {
      //retornar info del store en un objeto
      title: state["title"] || "",
      firstName: state["firstName"]  || "",
      lastName: state["lastName"]  || "",
      emailAddress: state["emailAddress"]  || "",
      country: state["country"]  || "",
      townCity: state["townCity"]  || "",
      streetAddress: state["streetAddress"]  || "",
      zipCode: state["zipCode"]  || "",
      phoneNumber1: state["phoneNumber1"]  || "",
      phoneNumber2: state["phoneNumber2"]  || "",
      phoneNumber3: state["phoneNumber3"]  || "",
      companyName: state["companyName"]  || "",
      companyTaxNumber: state["companyTaxNumber"]  || "",
      companyPhoneNumber: state["companyPhoneNumber"]  || "",
      documentationType: state["documentationType"]  || "",
      documentCode: state["documentCode"]  || "",
      expirationDate: state["expirationDate"]  || "",
      dateBirth: state["dateBirth"]  || "",
    };
  },
  checkUpdateLogin: async () => {
    const state = get();
    if (!state["isLoggedIn"]) {
      return false;
    }
    const updateTokens = state["updateTokens"];
    const result = await updateTokens();
    if (result.error) {
      // Comprobar el motivo del error y decidir si hacer signOut o no
      if (result.error === "expired_token") {
        state["signOut"]();
      }
      return false;
    }
    return true;
  },
  updateTokens: async () => {
    const {
      accessToken,
      refreshToken,
      setAccessToken,
      setRefreshToken,
      signOut,
    } = get();

    if (!accessToken) {
      return { error: "No se ha establecido el token de acceso" };
    }

    try {
      const data = await updateAccessToken(accessToken);

      if (data.accessToken) {
        setAccessToken(data.accessToken);
        return { error: null };
      } else if (data.tokenError && data.tokenError == "expired_token") {
        const refreshData = await updateRefreshToken(refreshToken);

        if (refreshData.tokenError) {
          const errorResponse = handleError({
            error: refreshData.tokenError,
            signOut,
          });
          return { error: errorResponse };
        }

        setAccessToken(refreshData.accessToken);

        if (refreshData.refreshToken) {
          setRefreshToken(refreshData.refreshToken);
        }

        return { error: null };
      }

      return { error: null };
    } catch (error) {
      // Manejar cualquier error que pueda ocurrir durante las llamadas a la API
      console.error(error);
      return { error: "Ha ocurrido un error al actualizar los tokens" };
    }
  },
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
  setCountry: (country) => {
    set({ country });
  },
  setTownCity: (townCity) => {
    set({ townCity });
  },
  setStreetAddress: (streetAddress) => {
    set({ streetAddress });
  },
  setZipCode: (zipCode) => {
    set({ zipCode });
  },
  setPhoneNumber1: (phoneNumber1) => {
    set({ phoneNumber1 });
  },
  setPhoneNumber2: (phoneNumber2) => {
    set({ phoneNumber2 });
  },
  setPhoneNumber3: (phoneNumber3) => {
    set({ phoneNumber3 });
  },
  setCompanyName: (companyName) => {
    set({ companyName });
  },
  setCompanyTaxNumber: (companyTaxNumber) => {
    set({ companyTaxNumber });
  },
  setCompanyPhoneNumber: (companyPhoneNumber) => {
    set({ companyPhoneNumber });
  },
  setDocumentationType: (documentationType) => {
    set({ documentationType });
  },
  setDocumentCode: (documentCode) => {
    set({ documentCode });
  },
  setExpirationDate: (expirationDate) => {
    set({ expirationDate });
  },
  setDateBirth: (dateBirth) => {
    set({ dateBirth });
  },
  setEmailAddress: (emailAddress) => {
    saveToNestedKeyInLocalStorage(
      ["userData", "emailAddress"],
      emailAddress || ""
    );
    set({ emailAddress });
  },
  signInStore: async (data) => {
    const response = await signIn({ ...data, get });
    if (response.status) {
      get()["activateAutoUpdateToken"]();
      toast.success("Se ha iniciado sesión");
    }
    return response;
  },
  googleSignInStore: async (data) => {
    const response = await googleSignIn({ credential: data.credential, get });
    if (response.status) {
      get()["activateAutoUpdateToken"]();
      toast.success("Se ha iniciado sesión");
    }
    return response;
  },
  signOut: async () => {
    get()["desactivateAutoUpdateToken"]();
    signOut({ set });
  },
}));
