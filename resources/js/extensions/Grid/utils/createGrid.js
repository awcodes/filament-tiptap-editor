import { createColumn } from "./createColumn";
import { getGridNodeTypes } from "./getGridNodeTypes";

export function createGrid(schema, colsCount, type, colContent) {
  const types = getGridNodeTypes(schema);
  const cols = [];

  for (let index = 0; index < colsCount; index += 1) {
    const col = createColumn(types.column, colContent);

    if (col) {
      cols.push(col);
    }
  }

  return types.grid.createChecked({ cols: colsCount, type: type }, cols);
}
