import { FC, ReactElement } from "react";
import styles from "./Footer.module.css";
import ErrorBoundary from "../ErrorBoundary/ErrorBoundary";
import { Suspense } from "react";
import { CompanyInfo } from "../CompanyInfo/CompanyInfo";

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
