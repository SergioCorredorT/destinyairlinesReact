import styles from "./CompanyInfo.module.css";
import logo from '../../images/Branding/isologo.PNG';
import { fetchData } from "../../services/fetchData";


const dataSending = {
  command: "getCompanyInfo"
};

const options = {
  method: "POST",
  headers: {
    "Content-Type": "application/json",
  },
  body: JSON.stringify(dataSending),
};

const apiData = fetchData(
  import.meta.env.VITE_API_URL,
  options
);

export function CompanyInfo() {
  const data = apiData.read();
  const { airlineAddress, phoneNumber, airlineEmail, legalInfo } = data.response;

  return (
    <>
      <div className={styles.companyInfo}>
        <img src={logo}/>
        <div><p><span className={styles.pTitle}>Dirección:</span> {airlineAddress}</p></div>
        <div><p><span className={styles.pTitle}>Teléfono:</span> {phoneNumber}</p></div>
        <div><p><span className={styles.pTitle}>Email:</span> {airlineEmail}</p></div>
        <div><p><span className={styles.pTitle}>Información Legal:</span> {legalInfo}</p></div>
      </div>
    </>
  );
}
