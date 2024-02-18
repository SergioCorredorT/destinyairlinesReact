import { useCallback, useEffect, useState } from "react";
import styles from "./UpdateUser.module.css";
import { authStore } from "../../store/authStore";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { updateUserSchema } from "../../validations/updateUserSchema";
import { optionsStore } from "../../store/optionsStore";
import { Modal } from "../Modal/Modal";
import { toast } from "react-toastify";
import { updateUser } from "../../services/updateUser";

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

type optionsForUserRegister = {
  docTypesEs?: { [key: string]: string };
  docTypesAndRegExp?: { [key: string]: string };
  titles?: { [key: string]: string };
  countries?: { [key: string]: string };
};

export const UpdateUser = ({ isDetailsOpen }: { isDetailsOpen: boolean }) => {
  const [initialValues, setInitialValues] = useState({});
  const [state, setState] = useState({
    generalError: "",
    optionsForUpdateUser: null as optionsForUserRegister | null,
    isLoadingUserData: true,
    isLoadingOptions: true,
    isLoadingUserUpdate: false,
  });
  const { getUserEditableInfo, setUserEditableInfo, accessToken, emailAddress } = authStore();
  const { getOptions } = optionsStore();
  const {
    getValues,
    register,
    handleSubmit,
    reset,
    formState: { errors: formErrors, dirtyFields },
  } = useForm<Inputs>({
    resolver: zodResolver(updateUserSchema),
  });

  const helperOptionsForUserRegister = useCallback(async () => {
    const response = await getOptions({
      countries: true,
      titles: true,
      docTypesEs: true,
      docTypesAndRegExp: true,
    });
    if (!response) {
      setState((prevState) => ({
        ...prevState,
        generalError: "No se pudieron cargar las opciones del formulario",
        isLoadingOptions: false,
      }));
      return;
    }
    setState((prevState) => ({
      ...prevState,
      generalError: "",
      isLoadingOptions: false,
      optionsForUpdateUser: response,
    }));
  }, [getOptions]);

  useEffect(() => {
    helperOptionsForUserRegister();
  }, [helperOptionsForUserRegister]);

  useEffect(() => {
    if (isDetailsOpen) {
      const getUserInfo = async () => {
        const userEditableInfo = await getUserEditableInfo();
        if (!userEditableInfo) {
          setState((prevState) => ({
            ...prevState,
            generalError: "No se pudo cargar los datos de usuario guardados",
            isLoadingUserData: false,
          }));
          return;
        }
        if (userEditableInfo.error) {
          setState((prevState) => ({
            ...prevState,
            generalError: userEditableInfo.error as string,
            isLoadingUserData: false,
          }));
          return;
        }

        reset(userEditableInfo);
        setInitialValues(userEditableInfo);
        setState((prevState) => ({
          ...prevState,
          generalError: "",
          isLoadingUserData: false,
        }));
      };
      getUserInfo();
    }
  }, [isDetailsOpen]);

  const handleSubmitUpdateUser = handleSubmit(async (jsonData) => {
    setState((prevState) => ({
      ...prevState,
      isLoadingUserUpdate: true,
    }));
    const currentValues = getValues();
    const changedValues = Object.keys(currentValues).reduce((acc, key) => {
      if ((currentValues as any)[key] !== (initialValues as any)[key]) {
        (acc as any)[key] = (currentValues as any)[key];
      }
      return acc;
    }, {});

    if (Object.keys(changedValues).length === 0) {
      toast.warning("No se ha modificado ningún campo");
      return;
    }

    const response = await updateUser({ newUserInfo: changedValues, accessToken, emailAddressAuth: emailAddress });
    if (!response) {
      setState((prevState) => ({
        ...prevState,
        generalError: "Error en la petición a servidor",
        isLoadingUserUpdate: false,
      }));
      return;
    }
    if (!response.status) {
      setState((prevState) => ({
        ...prevState,
        generalError: response.message,
        isLoadingUserUpdate: false,
      }));
      return;
    }
    toast.success("Datos actualizados correctamente");

    setUserEditableInfo(changedValues);
    reset(changedValues);
    setState((prevState) => ({
      ...prevState,
      isLoadingUserUpdate: false,
    }));
  });
  return (
    <>
      <form onSubmit={handleSubmitUpdateUser}>
        <Modal
          isOpen={state.isLoadingOptions || state.isLoadingUserData || state.isLoadingUserUpdate}
          closeButton={false}
        >
          <div>Cargando...</div>
        </Modal>
        <div className={styles.inputsContainer}>
          <div className={styles.inputContainer}>
            {formErrors.documentationType ? (
              <label
                htmlFor="documentationType"
                className={styles.errorMessage}
              >
                {formErrors.documentationType.message}
              </label>
            ) : (
              <label htmlFor="documentationType">Tipo de documento*</label>
            )}
            <select
              id="documentationType"
              title="Tipo de Documentación"
              {...register("documentationType")}
            >
              <option value="" disabled>
                Seleccione una opción
              </option>
              {state.optionsForUpdateUser &&
                state.optionsForUpdateUser.docTypesEs &&
                Object.keys(state.optionsForUpdateUser.docTypesEs).map(
                  (docTypeKey) => (
                    <option key={docTypeKey} value={docTypeKey}>
                      {state.optionsForUpdateUser &&
                        state.optionsForUpdateUser.docTypesEs &&
                        state.optionsForUpdateUser.docTypesEs[docTypeKey]}
                    </option>
                  )
                )}
            </select>
          </div>
          <div className={styles.inputContainer}>
            {formErrors.documentCode ? (
              <label htmlFor="documentCode" className={styles.errorMessage}>
                {formErrors.documentCode.message}
              </label>
            ) : (
              <label htmlFor="documentCode">Código de Documento*</label>
            )}
            <input
              type="text"
              id="documentCode"
              placeholder="Código de Documento"
              title="Código de Documento"
              {...register("documentCode")}
            />
          </div>
          <div className={styles.inputContainer}>
            {formErrors.expirationDate ? (
              <label htmlFor="expirationDate" className={styles.errorMessage}>
                {formErrors.expirationDate.message}
              </label>
            ) : (
              <label htmlFor="expirationDate">Fecha de caducidad*</label>
            )}
            <input
              type="date"
              id="expirationDate"
              placeholder="Fecha de Expiración"
              title="Fecha de Expiración"
              {...register("expirationDate")}
            />
          </div>
          <div className={styles.inputContainer}>
            {formErrors.title ? (
              <label htmlFor="title" className={styles.errorMessage}>
                {formErrors.title.message}
              </label>
            ) : (
              <label htmlFor="title">Título</label>
            )}
            <select
              id="title"
              title="Título"
              defaultValue=""
              {...register("title")}
            >
              <option value="" disabled>
                Seleccione una opción
              </option>
              {state.optionsForUpdateUser &&
                state.optionsForUpdateUser.titles &&
                Object.keys(state.optionsForUpdateUser.titles).map((title) => (
                  <option key={title} value={title}>
                    {state.optionsForUpdateUser &&
                      state.optionsForUpdateUser.titles &&
                      state.optionsForUpdateUser.titles[title]}
                  </option>
                ))}
            </select>
          </div>
          <div className={styles.inputContainer}>
            {formErrors.firstName ? (
              <label htmlFor="firstName" className={styles.errorMessage}>
                {formErrors.firstName.message}
              </label>
            ) : (
              <label htmlFor="firstName">Nombre*</label>
            )}
            <input
              type="text"
              id="firstName"
              placeholder="Nombre"
              title="Nombre"
              {...register("firstName")}
            />
          </div>
          <div className={styles.inputContainer}>
            {formErrors.lastName ? (
              <label htmlFor="lastName" className={styles.errorMessage}>
                {formErrors.lastName.message}
              </label>
            ) : (
              <label htmlFor="lastName">Apellidos*</label>
            )}
            <input
              type="text"
              id="lastName"
              placeholder="Apellido"
              title="Apellido"
              {...register("lastName")}
            />
          </div>
          <div className={styles.inputContainer}>
            {formErrors.townCity ? (
              <label htmlFor="townCity" className={styles.errorMessage}>
                {formErrors.townCity.message}
              </label>
            ) : (
              <label htmlFor="townCity">Ciudad*</label>
            )}
            <input
              type="text"
              id="townCity"
              placeholder="Ciudad"
              title="Ciudad"
              {...register("townCity")}
            />
          </div>
          <div className={styles.inputContainer}>
            {formErrors.streetAddress ? (
              <label htmlFor="streetAddress" className={styles.errorMessage}>
                {formErrors.streetAddress.message}
              </label>
            ) : (
              <label htmlFor="streetAddress">Dirección*</label>
            )}
            <input
              type="text"
              id="streetAddress"
              placeholder="Dirección"
              title="Dirección"
              {...register("streetAddress")}
            />
          </div>
          <div className={styles.inputContainer}>
            {formErrors.zipCode ? (
              <label htmlFor="zipCode" className={styles.errorMessage}>
                {formErrors.zipCode.message}
              </label>
            ) : (
              <label htmlFor="zipCode">Código postal*</label>
            )}
            <input
              type="text"
              id="zipCode"
              placeholder="Código Postal"
              title="Código Postal"
              {...register("zipCode")}
            />
          </div>
          <div className={styles.inputContainer}>
            {formErrors.country ? (
              <label htmlFor="country" className={styles.errorMessage}>
                {formErrors.country.message}
              </label>
            ) : (
              <label htmlFor="country">País*</label>
            )}
            <select
              defaultValue=""
              id="country"
              title="Introduzca aquí su país"
              {...register("country")}
            >
              <option value="" disabled>
                Seleccione una opción
              </option>
              {state.optionsForUpdateUser &&
                state.optionsForUpdateUser.countries &&
                Object.keys(state.optionsForUpdateUser.countries).map(
                  (country) => (
                    <option key={country} value={country}>
                      {country}
                    </option>
                  )
                )}
            </select>
          </div>
          <div className={styles.inputContainer}>
            {formErrors.phoneNumber1 ? (
              <label htmlFor="phoneNumber1" className={styles.errorMessage}>
                {formErrors.phoneNumber1.message}
              </label>
            ) : (
              <label htmlFor="phoneNumber1">Número de teléfono 1*</label>
            )}
            <input
              type="text"
              id="phoneNumber1"
              placeholder="Teléfono 1"
              title="Teléfono 1"
              {...register("phoneNumber1")}
            />
          </div>
          <div className={styles.inputContainer}>
            {formErrors.phoneNumber2 ? (
              <label htmlFor="phoneNumber2" className={styles.errorMessage}>
                {formErrors.phoneNumber2.message}
              </label>
            ) : (
              <label htmlFor="phoneNumber2">Número de teléfono 2</label>
            )}
            <input
              type="text"
              id="phoneNumber2"
              placeholder="Teléfono 2"
              title="Teléfono 2"
              {...register("phoneNumber2")}
            />
          </div>
          <div className={styles.inputContainer}>
            {formErrors.phoneNumber3 ? (
              <label htmlFor="phoneNumber3" className={styles.errorMessage}>
                {formErrors.phoneNumber3.message}
              </label>
            ) : (
              <label htmlFor="phoneNumber3">Número de teléfono 3</label>
            )}
            <input
              type="text"
              id="phoneNumber3"
              placeholder="Teléfono 3"
              title="Teléfono 3"
              {...register("phoneNumber3")}
            />
          </div>
          <div className={styles.inputContainer}>
            {formErrors.companyName ? (
              <label htmlFor="companyName" className={styles.errorMessage}>
                {formErrors.companyName.message}
              </label>
            ) : (
              <label htmlFor="companyName">Nombre de empresa</label>
            )}
            <input
              type="text"
              id="companyName"
              placeholder="Nombre de la Empresa"
              title="Nombre de la Empresa"
              {...register("companyName")}
            />
          </div>
          <div className={styles.inputContainer}>
            {formErrors.companyTaxNumber ? (
              <label htmlFor="companyTaxNumber" className={styles.errorMessage}>
                {formErrors.companyTaxNumber.message}
              </label>
            ) : (
              <label htmlFor="companyTaxNumber">
                Número de identificación fiscal
              </label>
            )}
            <input
              type="text"
              id="companyTaxNumber"
              placeholder="Número de Impuesto de la Empresa"
              title="Número de Impuesto de la Empresa"
              {...register("companyTaxNumber")}
            />
          </div>
          <div className={styles.inputContainer}>
            {formErrors.companyPhoneNumber ? (
              <label
                htmlFor="companyPhoneNumber"
                className={styles.errorMessage}
              >
                {formErrors.companyPhoneNumber.message}
              </label>
            ) : (
              <label htmlFor="companyPhoneNumber">
                Número de teléfono de su empresa
              </label>
            )}
            <input
              type="text"
              id="companyPhoneNumber"
              placeholder="Teléfono de la Empresa"
              title="Teléfono de la Empresa"
              {...register("companyPhoneNumber")}
            />
          </div>
          <div className={styles.inputContainer}>
            {formErrors.dateBirth ? (
              <label htmlFor="dateBirth" className={styles.errorMessage}>
                {formErrors.dateBirth.message}
              </label>
            ) : (
              <label htmlFor="dateBirth">Fecha de nacimiento*</label>
            )}
            <input
              type="date"
              id="dateBirth"
              placeholder="Fecha de Nacimiento"
              title="Fecha de Nacimiento"
              {...register("dateBirth")}
            />
          </div>
        </div>
        <div className={styles.buttonsContainer}>
          <button
            disabled={Object.keys(dirtyFields).length === 0}
            type="submit"
          >
            Guardar cambios
          </button>
        </div>
      </form>
      {state.generalError && (
        <div className={styles.errorMessage}>
          <p>{state.generalError}</p>
        </div>
      )}
    </>
  );
};
