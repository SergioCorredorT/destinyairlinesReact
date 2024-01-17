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
  "http://localhost/destinyairlinesReact/proyecto/destinyAirlines/backend/MainController.php",
  options
);

function CompanyInfo() {
  const data = apiData.read();
  const { airlineAddress, phoneNumber, airlineEmail, legalInfo } = data.response;

  return (
    <>
      <div className={styles.companyInfo}>
        <img src={logo}/>
        <p>Dirección: {airlineAddress}</p>
        <p>Teléfono: {phoneNumber}</p>
        <p>Email: {airlineEmail}</p>
        <p>Información Legal: {legalInfo}</p>
      </div>
    </>
  );
}

export default CompanyInfo;
