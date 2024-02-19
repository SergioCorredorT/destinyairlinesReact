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

interface AuthStoreState {
  isLoggedIn: boolean;
  updateTime: number;
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
  signIn: ({
    emailAddress,
    password,
  }: {
    emailAddress: string;
    password: string;
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
    for (const key in newUserInfo) {
      const value = newUserInfo[key];
      switch (key) {
        case "title":
          get().setTitle(value || "");
          break;
        case "firstName":
          get().setFirstName(value || "");
          break;
        case "lastName":
          get().setLastName(value || "");
          break;
        case "country":
          get().setCountry(value || "");
          break;
        case "townCity":
          get().setTownCity(value || "");
          break;
        case "streetAddress":
          get().setStreetAddress(value || "");
          break;
        case "zipCode":
          get().setZipCode(value || "");
          break;
        case "phoneNumber1":
          get().setPhoneNumber1(value || "");
          break;
        case "phoneNumber2":
          get().setPhoneNumber2(value || "");
          break;
        case "phoneNumber3":
          get().setPhoneNumber3(value || "");
          break;
        case "companyName":
          get().setCompanyName(value || "");
          break;
        case "companyTaxNumber":
          get().setCompanyTaxNumber(value || "");
          break;
        case "companyPhoneNumber":
          get().setCompanyPhoneNumber(value || "");
          break;
        case "documentationType":
          get().setDocumentationType(value || "");
          break;
        case "documentCode":
          get().setDocumentCode(value || "");
          break;
        case "expirationDate":
          get().setExpirationDate(value || "");
          break;
        case "dateBirth":
          get().setDateBirth(value || "");
          break;
        default:
          console.warn(`No setter found for key "${key}"`);
      }
    }
    set({ ["isSaveSecondaryUserData"]: true });
  },
  getUserEditableInfo: async (forceFetch = false) => {
    if (!get()["isLoggedIn"]) {
      return { error: "El usuario no está logueado" };
    }
    //Comprobando si no tenemos la info de usuario editable en el store o si el forceFetch está activado (se usará tras el update de datos)
    if (!get()["isSaveSecondaryUserData"] || forceFetch) {
      //El emailAddress siempre se carga por estar también en el localstorage
      const emailAddress = get()["emailAddress"];
      const isLogued = await get()["checkUpdateLogin"]();
      if (!isLogued) {
        return { error: "Error en la comprobación de tokens" };
      }
      const accessToken = get()["accessToken"];
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
      title: get()["title"],
      firstName: get()["firstName"],
      lastName: get()["lastName"],
      emailAddress: get()["emailAddress"],
      country: get()["country"],
      townCity: get()["townCity"],
      streetAddress: get()["streetAddress"],
      zipCode: get()["zipCode"],
      phoneNumber1: get()["phoneNumber1"],
      phoneNumber2: get()["phoneNumber2"],
      phoneNumber3: get()["phoneNumber3"],
      companyName: get()["companyName"],
      companyTaxNumber: get()["companyTaxNumber"],
      companyPhoneNumber: get()["companyPhoneNumber"],
      documentationType: get()["documentationType"],
      documentCode: get()["documentCode"],
      expirationDate: get()["expirationDate"],
      dateBirth: get()["dateBirth"],
    };
  },
  checkUpdateLogin: async () => {
    if (!get()["isLoggedIn"]) {
      return false;
    }
    const updateTokens = get()["updateTokens"];
    const result = await updateTokens();
    if (result.error) {
      // Comprobar el motivo del error y decidir si hacer signOut o no
      if (result.error === "expired_token") {
        get()["signOut"]();
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
    //saveToNestedKeyInLocalStorage(["userData", "country"], country || "");
    set({ country });
  },
  setTownCity: (townCity) => {
    //saveToNestedKeyInLocalStorage(["userData", "townCity"], townCity || "");
    set({ townCity });
  },
  setStreetAddress: (streetAddress) => {
    //saveToNestedKeyInLocalStorage(["userData", "streetAddress"], streetAddress || "");
    set({ streetAddress });
  },
  setZipCode: (zipCode) => {
    //saveToNestedKeyInLocalStorage(["userData", "zipCode"], zipCode || "");
    set({ zipCode });
  },
  setPhoneNumber1: (phoneNumber1) => {
    //saveToNestedKeyInLocalStorage(["userData", "phoneNumber1"], phoneNumber1 || "");
    set({ phoneNumber1 });
  },
  setPhoneNumber2: (phoneNumber2) => {
    //saveToNestedKeyInLocalStorage(["userData", "phoneNumber2"], phoneNumber2 || "");
    set({ phoneNumber2 });
  },
  setPhoneNumber3: (phoneNumber3) => {
    //saveToNestedKeyInLocalStorage(["userData", "phoneNumber3"], phoneNumber3 || "");
    set({ phoneNumber3 });
  },
  setCompanyName: (companyName) => {
    //saveToNestedKeyInLocalStorage(["userData", "companyName"], companyName || "");
    set({ companyName });
  },
  setCompanyTaxNumber: (companyTaxNumber) => {
    //saveToNestedKeyInLocalStorage(["userData", "companyTaxNumber"], companyTaxNumber || "");
    set({ companyTaxNumber });
  },
  setCompanyPhoneNumber: (companyPhoneNumber) => {
    //saveToNestedKeyInLocalStorage(["userData", "companyPhoneNumber"], companyPhoneNumber || "");
    set({ companyPhoneNumber });
  },
  setDocumentationType: (documentationType) => {
    //saveToNestedKeyInLocalStorage(["userData", "documentationType"], documentationType || "");
    set({ documentationType });
  },
  setDocumentCode: (documentCode) => {
    //saveToNestedKeyInLocalStorage(["userData", "documentCode"], documentCode || "");
    set({ documentCode });
  },
  setExpirationDate: (expirationDate) => {
    //saveToNestedKeyInLocalStorage(["userData", "expirationDate"], expirationDate || "");
    set({ expirationDate });
  },
  setDateBirth: (dateBirth) => {
    //saveToNestedKeyInLocalStorage(["userData", "dateBirth"], dateBirth || "");
    set({ dateBirth });
  },
  setEmailAddress: (emailAddress) => {
    saveToNestedKeyInLocalStorage(
      ["userData", "emailAddress"],
      emailAddress || ""
    );
    set({ emailAddress });
  },
  signIn: (data) => signIn({ ...data, get }),
  signOut: () => signOut({ set }),
}));
