import styles from "./App.module.css";
import { Header } from "../Header/Header";
import { Main } from "../Main/Main";
import { Footer } from "../Footer/Footer";
import { useEffect } from "react";
import {
  getFromLocalStorage,
  getToNestedKeyInLocalStorage,
} from "../../tools/localStorageUtils";
import { authStore } from "../../store/authStore";
import { ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

export function App() {
  const {
    setAccessToken,
    setRefreshToken,
    setTitle,
    setFirstName,
    setLastName,
    setEmailAddress,
    setIsLoggedIn,
    checkUpdateLogin,
    activateAutoUpdateToken,
    desactivateAutoUpdateToken
  } = authStore();

  useEffect(() => {
    if (getFromLocalStorage("isLoggedIn")) {
      setIsLoggedIn(true);
      setAccessToken(getToNestedKeyInLocalStorage(["tokens", "accessToken"]));
      setRefreshToken(getToNestedKeyInLocalStorage(["tokens", "refreshToken"]));
      setTitle(getToNestedKeyInLocalStorage(["userData", "title"]) || "");
      setFirstName(
        getToNestedKeyInLocalStorage(["userData", "firstName"]) || ""
      );
      setLastName(getToNestedKeyInLocalStorage(["userData", "lastName"]) || "");
      setEmailAddress(
        getToNestedKeyInLocalStorage(["userData", "emailAddress"])
      );
      activateAutoUpdateToken();
    }
    else{
      desactivateAutoUpdateToken();
    }
    checkUpdateLogin();
  }, []);
  return (
    <div className={styles.container}>
      <Header />
      <Main />
      <Footer />
      <ToastContainer />
    </div>
  );
}
