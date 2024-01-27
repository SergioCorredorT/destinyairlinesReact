import { create } from "zustand";
import { destinyAirlinesFetch } from "../services/fetchUtils";
import { toast } from "react-toastify";

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

    //Comprobamos cuales son las key requeridas que no tenemos previamente cargadas en el store
    for (const option in listOptions) {
      if (listOptions[option as keyof ListOptions] && !get()[option as keyof ListOptions]) {
        optionsFetchRequired[option as keyof ListOptions] = true;
      }
    }

    const response = await destinyAirlinesFetch({
      command: "getOptions",
      listOptions: optionsFetchRequired,
    });

    try {
      if (!response.status) {
        throw new Error("Hubo un error en la petición a servidor");
      }

      //Vamos introduciendo en la store los datos recibidos del fetch y rellenamos la respuesta
      for (const option in listOptions) {
          if (optionsFetchRequired[option as keyof ListOptions]) {
            set({ [option]: response.response[option] });
          }
          rsp[option] = get()[option as keyof ListOptions];
      }
    } catch (e: any) {
      toast.error(e.message);
    }
    return rsp;
  },
}));
