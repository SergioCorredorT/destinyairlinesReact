import { useEffect, useState } from "react";
import styles from "./UpdateUser.module.css";
import { useAuthStore } from "../../store/authStore";
import { userEditableDataStore } from "../../store/userEditableDataStore";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { updateUserSchema } from "../../validations/updateUserSchema";

type Inputs = {
  firstName: string;
  lastName: string;
  country: string;
  townCity: string;
  streetAddress: string;
  zipCode: string;
  documentationType: string;
  documentCode: string;
  expirationDate: string;
  title: string;
  phoneNumber1: string;
  phoneNumber2: string;
  phoneNumber3: string;
  companyName: string;
  companyTaxNumber: string;
  companyPhoneNumber: string;
  dateBirth: string;
};

export const UpdateUser = ({ isDetailsOpen }: { isDetailsOpen: boolean }) => {
  const [generalError, setGeneralError] = useState("");
  const { getUserEditableInfo } = useAuthStore();
  const { userEditableInfo, setUserEditableInfo } = userEditableDataStore();

  useEffect(() => {
    if (isDetailsOpen) {
      const getUserInfo = async () => {
        const userEditableInfo = await getUserEditableInfo();
        if (!userEditableInfo) {
          setGeneralError("Error en la petición a servidor");
          return;
        }
        if (userEditableInfo.error) {
          setGeneralError(userEditableInfo.error);
          return;
        }
        setGeneralError("");
        setUserEditableInfo(userEditableInfo);
      };
      getUserInfo();
    }
  }, [isDetailsOpen]);

  const {
    register,
    handleSubmit,
    formState: { errors: formErrors },
  } = useForm<Inputs>({
    resolver: zodResolver(updateUserSchema),
  });

  const handleSubmitUpdateUser = handleSubmit((jsonData) => {
    //Validar solo los campos modificados (si no hay ninguno, que se mantenga inhabilitado el button submit)

    //enviar tales campos al backend (pendiente de crear el service UpdateUser), y si no falla el update en backend, guardar en el store zustand los datos del user
    //Escribir los errores de backend si los hay o un "success" si no
  });
  return (
    <>
      <form onSubmit={handleSubmitUpdateUser}>
        <div className={styles.inputsContainer}>
          <div className={styles.inputContainer}>
            <label htmlFor="documentationType">Tipo de Documentación</label>
            <input
              type="text"
              id="documentationType"
              placeholder="Tipo de Documentación"
              title="Tipo de Documentación"
              value={userEditableInfo.documentationType || ""}
            />
          </div>
          <div className={styles.inputContainer}>
            <label htmlFor="documentCode">Código de Documento</label>
            <input
              type="text"
              id="documentCode"
              placeholder="Código de Documento"
              title="Código de Documento"
              value={userEditableInfo.documentCode || ""}
            />
          </div>
          <div className={styles.inputContainer}>
            <label htmlFor="expirationDate">Fecha de Expiración</label>
            <input
              type="date"
              id="expirationDate"
              placeholder="Fecha de Expiración"
              title="Fecha de Expiración"
              value={userEditableInfo.expirationDate || ""}
            />
          </div>
          <div className={styles.inputContainer}>
            <label htmlFor="title">Título</label>
            <input
              type="text"
              id="title"
              placeholder="Título"
              title="Título"
              value={userEditableInfo.title || ""}
            />
          </div>
          <div className={styles.inputContainer}>
            <label htmlFor="firstName">Nombre</label>
            <input
              type="text"
              id="firstName"
              placeholder="Nombre"
              title="Nombre"
              value={userEditableInfo.firstName || ""}
            />
          </div>
          <div className={styles.inputContainer}>
            <label htmlFor="lastName">Apellido</label>
            <input
              type="text"
              id="lastName"
              placeholder="Apellido"
              title="Apellido"
              value={userEditableInfo.lastName || ""}
            />
          </div>
          <div className={styles.inputContainer}>
            <label htmlFor="townCity">Ciudad</label>
            <input
              type="text"
              id="townCity"
              placeholder="Ciudad"
              title="Ciudad"
              value={userEditableInfo.townCity || ""}
            />
          </div>
          <div className={styles.inputContainer}>
            <label htmlFor="streetAddress">Dirección</label>
            <input
              type="text"
              id="streetAddress"
              placeholder="Dirección"
              title="Dirección"
              value={userEditableInfo.streetAddress || ""}
            />
          </div>
          <div className={styles.inputContainer}>
            <label htmlFor="zipCode">Código Postal</label>
            <input
              type="text"
              id="zipCode"
              placeholder="Código Postal"
              title="Código Postal"
              value={userEditableInfo.zipCode || ""}
            />
          </div>
          <div className={styles.inputContainer}>
            <label htmlFor="country">País</label>
            <input
              type="text"
              id="country"
              placeholder="País"
              title="País"
              value={userEditableInfo.country || ""}
            />
          </div>
          <div className={styles.inputContainer}>
            <label htmlFor="password">Contraseña</label>
            <input
              type="text"
              id="password"
              placeholder="Contraseña"
              title="Contraseña"
              value={userEditableInfo.password || ""}
            />
          </div>
          <div className={styles.inputContainer}>
            <label htmlFor="phoneNumber1">Teléfono 1</label>
            <input
              type="text"
              id="phoneNumber1"
              placeholder="Teléfono 1"
              title="Teléfono 1"
              value={userEditableInfo.phoneNumber1 || ""}
            />
          </div>
          <div className={styles.inputContainer}>
            <label htmlFor="phoneNumber2">Teléfono 2</label>
            <input
              type="text"
              id="phoneNumber2"
              placeholder="Teléfono 2"
              title="Teléfono 2"
              value={userEditableInfo.phoneNumber2 || ""}
            />
          </div>
          <div className={styles.inputContainer}>
            <label htmlFor="phoneNumber3">Teléfono 3</label>
            <input
              type="text"
              id="phoneNumber3"
              placeholder="Teléfono 3"
              title="Teléfono 3"
              value={userEditableInfo.phoneNumber3 || ""}
            />
          </div>
          <div className={styles.inputContainer}>
            <label htmlFor="companyName">Nombre de la Empresa</label>
            <input
              type="text"
              id="companyName"
              placeholder="Nombre de la Empresa"
              title="Nombre de la Empresa"
              value={userEditableInfo.companyName || ""}
            />
          </div>
          <div className={styles.inputContainer}>
            <label htmlFor="companyTaxNumber">
              Número de Impuesto de la Empresa
            </label>
            <input
              type="text"
              id="companyTaxNumber"
              placeholder="Número de Impuesto de la Empresa"
              title="Número de Impuesto de la Empresa"
              value={userEditableInfo.companyTaxNumber || ""}
            />
          </div>
          <div className={styles.inputContainer}>
            <label htmlFor="companyPhoneNumber">Teléfono de la Empresa</label>
            <input
              type="text"
              id="companyPhoneNumber"
              placeholder="Teléfono de la Empresa"
              title="Teléfono de la Empresa"
              value={userEditableInfo.companyPhoneNumber || ""}
            />
          </div>
          <div className={styles.inputContainer}>
            <label htmlFor="dateBirth">Fecha de Nacimiento</label>
            <input
              type="date"
              id="dateBirth"
              placeholder="Fecha de Nacimiento"
              title="Fecha de Nacimiento"
              value={userEditableInfo.dateBirth || ""}
            />
          </div>
        </div>
        <div className={styles.buttonsContainer}>
          <button type="submit">Guardar cambios</button>
        </div>
      </form>
      {generalError && (
        <div className={styles.errorMessage}>
          <p>{generalError}</p>
        </div>
      )}
    </>
  );
};
