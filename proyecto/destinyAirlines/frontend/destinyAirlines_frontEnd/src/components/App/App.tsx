import "normalize.css"
import styles from "./App.module.css"
import { Header } from "../Header/Header"
import { Main } from "../Main/Main"
import { Footer } from "../Footer/Footer"

export function App() {
  return (
    <div className={styles.container}>
      <Header />
      <Main />
      <Footer />
    </div>
  );
}
