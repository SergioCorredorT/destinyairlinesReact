/* export const getItemFromLocalStorage = (key, variable) => {
    const data = localStorage.getItem(key);
    if (data) {
        try {
            const parsedData = JSON.parse(data);
            return parsedData[variable] || false;
        } catch (error) {
            console.error("Error parsing data from localStorage:", error);
        }
    }
    return false;
} */

export const getToNestedKeyInLocalStorage = (keys) => {
    let root = getFromLocalStorage(keys[0]);

    if (!root) {
        return null;
    }

    let obj = root;
    for (let i = 1; i < keys.length; i++) {
        if (!obj[keys[i]]) {
            return null;
        }
        obj = obj[keys[i]];
    }

    return obj;
}

export const removeFromNestedKeyInLocalStorage = (keys) => {
    if (keys.length === 1) {
        // Si solo hay una key, elimina todo el objeto
        removeKeyFromLocalStorage(keys[0]);
    } else {
        // Si hay más de una key, elimina la propiedad específica del objeto
        let existingObject = getFromLocalStorage(keys[0]);

        if (existingObject) {
            let obj = existingObject;
            for (let i = 1; i < keys.length - 1; i++) {
                if (!obj[keys[i]]) {
                    return; // No hay nada que eliminar
                }
                obj = obj[keys[i]];
            }

            // Elimina la key final
            if (obj[keys[keys.length - 1]]) {
                delete obj[keys[keys.length - 1]];
            }

            // Guarda el objeto modificado de nuevo en el almacenamiento local
            saveToLocalStorage(keys[0], existingObject);
        }
    }
}

export const removeKeyFromLocalStorage = (key) => {
    localStorage.removeItem(key);
    return true;
}

export const removePropertyFromLocalStorage = (key, property) => {
    let existingObject = getFromLocalStorage(key);
    
    if (existingObject && property in existingObject) {
        delete existingObject[property];
        saveToLocalStorage(key, existingObject);
        return true;
    }
    return false;
}

export const saveToNestedKeyInLocalStorage = (keys, value) => {
    let root = getFromLocalStorage(keys[0]) || {};

    if (keys.length === 1) {
        root = value;
    } else {
        let obj = root;
        for (let i = 1; i < keys.length - 1; i++) {
            obj[keys[i]] = obj[keys[i]] || {};
            obj = obj[keys[i]];
        }
        obj[keys[keys.length - 1]] = value;
    }
    
    saveToLocalStorage(keys[0], root);
}

export const getAllKeysAndContentsFromLocalStorage = () => {
    const keys = Object.keys(localStorage);
    const contents = {};

    keys.forEach((key) => {
        contents[key] = getFromLocalStorage(key);
    });

    return contents;
}

export const clearAllFromLocalStorage = () => {
    localStorage.clear();
    return true;
}

export const itemExistsInLocalStorage = (key, item) => {
    const data = localStorage.getItem(key);
    
    if (data) {
        try {
            const parsedData = JSON.parse(data);
            return parsedData.hasOwnProperty(item);
        } catch (error) {
            console.error("Error parsing data from localStorage:", error);
        }
    }
    
    return false;
}
//Secondary
export const existsInLocalStorage = (key) => {
    return localStorage.getItem(key) !== null;
}

export const getFromLocalStorage = (key) => {
    const value = localStorage.getItem(key);
    return JSON.parse(value);
}

export const saveToLocalStorage = (key, value) => {
    if (typeof value === 'string') {
        localStorage.setItem(key, value);
    } else {
        localStorage.setItem(key, JSON.stringify(value));
    }
    return true;
}