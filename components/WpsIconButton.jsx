import * as React from "react";
import Button from "@mui/material/Button";
import SendIcon from "@mui/icons-material/Send";

export default function WpsIconButton(props) {
  return (
    <Button
      id={props.id || "wps-icon-btn"}
      className={props.className || "wps-icon-btn"}
      variant={props.variant || "contained"}
      endIcon={props.endIcon || <SendIcon />}
      href={props.href} // external or internal URL
      newTab={props.newTab || true}
      sx={{lineHeight: "1.5", ...props.sx}}
    >
      {props.children || "Send"}
    </Button>
  );
}
