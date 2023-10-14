function copiarAlPortapapeles() {
    const textarea = document.getElementById('generatedEntity');
    textarea.select(); // Selecciona el contenido del textarea

    try {
        navigator.clipboard.writeText(textarea.value); // Intenta copiar al portapapeles
    } catch (err) {
        console.error('Error al intentar copiar al portapapeles:', err);
    }
}


function generateEntity() {
    const sqlCode = document.getElementById('sqlCode').value;
    if((sqlCode.trim())!="")
    {
        const generatedEntity = generateEntityFromSQL(sqlCode);
        document.getElementById('generatedEntity').value = generatedEntity;
    }
}

function removeQuotes(myString) {
    return myString.replace(/['"`]/g, '');
}

function getTableName(sqlCode) {
    const tableIndex = sqlCode.indexOf("TABLE");
    
    if (tableIndex !== -1) {
        const nextSpaceIndex = sqlCode.indexOf(" ", tableIndex + 6);
        const nextParenthesisIndex = sqlCode.indexOf("(", tableIndex + 6);

        const endIndex = (nextSpaceIndex !== -1 && (nextParenthesisIndex === -1 || nextSpaceIndex < nextParenthesisIndex)) 
            ? nextSpaceIndex : nextParenthesisIndex;

        return removeQuotes(sqlCode.substring(tableIndex + 6, endIndex).trim());
    }
}

function removeContentAroundParentheses(inputString) {
    const firstOpenParenthesisIndex = inputString.indexOf("(");

    if (firstOpenParenthesisIndex !== -1) {
        const truncatedString = inputString.substring(firstOpenParenthesisIndex + 1);
        const lastCloseParenthesisIndex = truncatedString.lastIndexOf(")");

        
        if (lastCloseParenthesisIndex !== -1) {
            return truncatedString.substring(0, lastCloseParenthesisIndex).trim();
        }
    }

    return inputString;
}

function extractBeforeFirstParenthesis(inputString) {
    const firstOpenParenthesisIndex = inputString.indexOf("(");

    if (firstOpenParenthesisIndex !== -1) {
        return inputString.substring(0, firstOpenParenthesisIndex);
    }

    return inputString;
}

function extractBeforeNextSpace(inputString) {
    const nextSpaceIndex = inputString.indexOf(" ");

    if (nextSpaceIndex !== -1) {
        return inputString.substring(0, nextSpaceIndex);
    }

    return inputString;
}

function getNameColumn(string) {
    return removeQuotes(extractBeforeNextSpace(string));
}

function getNameColumns(columns) {
    return columns.map(getNameColumn);
}

function generateEntityFromSQL(sqlCode) {
    const headSql = extractBeforeFirstParenthesis(sqlCode);
    const tableName = getTableName(headSql);
    const bodySql = removeContentAroundParentheses(sqlCode);
    const bodySqlParticionado = bodySql.split(',').map(item => item.trim());
    const fields = getNameColumns(bodySqlParticionado);
    const className = tableName.charAt(0).toUpperCase() + tableName.toLowerCase().slice(1) + 'Entity';

    let code = `<?php\n\nclass ${className} {\n`;

    fields.forEach(field => {
        code += `    private $${field};\n`;
    });

    code += '\n';
    // Constructor con asignación de valores por defecto
    code += `    public function __construct($params = []) {\n`;
    fields.forEach(field => {
        code += `        $this->${field} = $params['${field}'] ?? null;\n`;
    });
    code += `    }\n`;

    fields.forEach(field => {
        const ucField = field.charAt(0).toUpperCase() + field.slice(1);
        code += '\n';
        code += `    public function get${ucField}() {\n`;
        code += `        return $this->${field};\n`;
        code += `    }\n`;
    });

    fields.forEach(field => {
        const ucField = field.charAt(0).toUpperCase() + field.slice(1);

        code += '\n';
        code += `    public function set${ucField}($value) {\n`;
        code += `        $this->${field} = $value;\n`;
        code += `    }\n`;
    });


    code += `}\n`;

    return code;
}
