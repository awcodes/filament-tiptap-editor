export function createBuilderColumn(colType, colSpan, colContent = null) {
  if (colContent) {
    return colType.createChecked({'data-col-span': colSpan}, colContent);
  }

  return colType.createAndFill({'data-col-span': colSpan});
}
