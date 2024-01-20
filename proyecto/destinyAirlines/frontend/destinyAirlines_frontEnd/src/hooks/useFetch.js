import { useState, useEffect } from 'react';

export const useFetch = (url = '', options = null) => {
    const [data, setData] = useState(null);
    const [error, setError] = useState(null);
    const [isLoading, setIsLoading] = useState(false);
    const [abortController, setAbortController] = useState(null);

    useEffect(() => {
        setAbortController(new AbortController());
        const fetchData = async () => {
            setIsLoading(true);
            try {
                const response = await fetch(url, { ...options, signal: abortController.signal });
                const json = await response.json();
                setData(json);
            } catch (error) {
                if (error.name === 'AbortError') {
                    console.log('Request aborted');
                } else {
                    setError(error);
                }
            } finally {
                setIsLoading(false);
            }
        };

        if(url){fetchData()};
        return () => abortController.abort();
    }, [url, options]);

    const handleCancelRequest = () => {
        if (abortController) {
            setIsLoading(false);
            abortController.abort();
            setError("Request canceled");
        }
    }

    return { data, error, isLoading, handleCancelRequest };
};

/*
//EJEMPLO DE USO
const data = { clave: 'valor' }; // Los datos que quieres enviar

const options = {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(data)
};

const { data, error, isLoading } = useFetch('http://localhost:5000/api/ruta', options); */
