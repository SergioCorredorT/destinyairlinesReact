import { lazy, FC, ReactElement, LazyExoticComponent } from "react";
import styles from "./Footer.module.css";
import ErrorBoundary from "../ErrorBoundary/ErrorBoundary";
import { Suspense } from "react";
// Aquí usamos React.lazy para hacer una importación dinámica de CompanyInfo
const CompanyInfo: LazyExoticComponent<FC> = lazy(
  () => import("../CompanyInfo/CompanyInfo")
);

export const Footer: FC = (): ReactElement => {
  return (
    <footer className={styles.footer}>
      <ErrorBoundary>
        <Suspense fallback={<div>Cargando...</div>}>
          <CompanyInfo />
        </Suspense>
      </ErrorBoundary>
    </footer>
  );
};
