import { createBuilderColumn } from "./createBuilderColumn";
import { getGridBuilderNodeTypes } from "./getGridBuilderNodeTypes";

export function createGridBuilder(schema, colsCount, type, stackAt, asymmetricLeft, asymmetricRight, colContent) {
  const types = getGridBuilderNodeTypes(schema);
  const cols = [];

  if (type === 'asymmetric') {
    cols.push(createBuilderColumn(types.builderColumn, asymmetricLeft, colContent));
    cols.push(createBuilderColumn(types.builderColumn, asymmetricRight, colContent));
  } else {
    for (let index = 0; index < colsCount; index += 1) {
      const col = createBuilderColumn(types.builderColumn, null, colContent);

      if (col) {
        cols.push(col);
      }
    }
  }

  return types.gridBuilder.createChecked({ 'data-cols': colsCount, 'data-type': type, 'data-stack-at': stackAt }, cols);
}
