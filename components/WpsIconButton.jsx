import * as React from "react";
import Button from "@mui/material/Button";
import SendIcon from "@mui/icons-material/Send";

export default function WpsIconButton({
  id = "wps-icon-btn",
  className = "wps-icon-btn",
  variant = "contained",
  endIcon = <SendIcon />,
  href,
  newTab = false,
  sx = {},
  children = "Send",
  ...rest
}) {
  return (
    <Button
      id={id}
      className={className}
      variant={variant}
      endIcon={endIcon}
      href={href}
      target={newTab ? "_blank" : undefined}
      rel={newTab ? "noopener noreferrer" : undefined}
      sx={{ lineHeight: "1.5", ...sx }}
      {...rest}
    >
      {children}
    </Button>
  );
}
