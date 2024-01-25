import { toast } from "react-toastify";
import { removeFromNestedKeyInLocalStorage } from "./localStorageUtils";

export function signOut({ set }) {
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
}