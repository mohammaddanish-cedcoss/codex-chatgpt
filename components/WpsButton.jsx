import * as React from "react";
import Button from "@mui/material/Button";

export default function WpsButton(props) {
  return (
    <Button
      id={props.id || "wps-btn"}
      className={props.className || "wps-btn"}
      variant={props.variant || "contained"}
      size={props.size || "small"}
      sx={{ display: "inline-block", borderRadius: "50px",padding: "12px", backgroundColor:"primary.purple" ,lineHeight:"1" }}
    >
      {props.children || "Click Me"}
    </Button>
  );
}
