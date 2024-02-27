import { create } from "zustand";
import { getOptions } from "../services/getOptions";

interface ListOptions {
  countries?: boolean;
  titles?: boolean;
  docTypesEs?: boolean;
  docTypesAndRegExp?: boolean;
}

interface OptionsStoreState {
  docTypesEs: { [key: string]: string } | null;
  titles: { [key: string]: string } | null;
  countries: { [key: string]: string } | null;
  docTypesAndRegExp: { [key: string]: string } | null;
  getOptions: (
    listOptions: ListOptions
  ) => Promise<{ [key: string]: { [key: string]: string } | null }>;
}

export const optionsStore = create<OptionsStoreState>((set, get) => ({
  countries: null,
  titles: null,
  docTypesEs: null,
  docTypesAndRegExp: null,
  getOptions: async (listOptions: ListOptions) => {
    const optionsFetchRequired: ListOptions = {};
    const rsp: { [key: string]: { [key: string]: string } | null } = {};

    // Comprobamos cuales son las key requeridas que no tenemos previamente cargadas en el store
    for (const option in listOptions) {
      if (!get()[option as keyof ListOptions]) {
        optionsFetchRequired[option as keyof ListOptions] = true;
      }
    }

    // Si hay opciones que necesitan ser buscadas, hacemos la petición
    if (Object.keys(optionsFetchRequired).length > 0) {
      const response = await getOptions({ listOptions: optionsFetchRequired });

      // Actualizamos el store con los datos recibidos y rellenamos la respuesta
      for (const option in optionsFetchRequired) {
        const optionValue = response?.response[option];
        if (optionValue) {
          set({ [option]: optionValue });
          rsp[option] = optionValue;
        }
      }
    }

    // Para las opciones que ya estaban en el store, simplemente las añadimos a la respuesta
    for (const option in listOptions) {
      if (!optionsFetchRequired[option as keyof ListOptions]) {
        rsp[option] = get()[option as keyof ListOptions];
      }
    }

    return rsp;
  },
}));
