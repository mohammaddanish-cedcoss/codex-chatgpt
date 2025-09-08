import * as React from "react";
import Typography from "@mui/material/Typography";
import { border, color, Stack } from "@mui/system";
import DOMPurify from "dompurify";
import WhatshotIcon from "@mui/icons-material/Whatshot";
import { keyframes } from "@mui/system";
import WpsIconButton from "@/components/WpsIconButton";

export default function WpsOfferStrip(props) {
  const safeOffer = DOMPurify.sanitize(
    props.offer ??
      "Flash Sale is live: Get <strong>up to 45% OFF</strong> on WP Swings Plugins -"
  );
  const zoomInOut = keyframes`
  0% { transform: scale(1); }
  50% { transform: scale(1.3); }
  100% { transform: scale(1); }
`;
  return (
    <Stack
      direction="row"
      spacing={1}
      flexWrap="wrap"
      gap={0.6}
      alignItems="center"
      justifyContent="center"
      borderRadius={0}
      sx={(theme) => ({
        background: `linear-gradient(90deg, ${theme.palette.primary.blue}, ${theme.palette.primary.purple})`,
        marginLeft: "-20px",
      })}
      p={1}
    >
      <WhatshotIcon
        sx={{
          color: "primary.white",
          animation: `${zoomInOut} 1.2s ease-in-out infinite`,
        }}
      />
      <Typography
        variant="body2"
        fontSize="14px"
        color="primary.white"
        lineHeight="1.5"
      >
        <span dangerouslySetInnerHTML={{ __html: safeOffer }} />{" "}
        <WpsIconButton
          size="small"
          variant="outline"
          href="#"
          sx={{
            border: "1.5px solid",
            borderColor: "primary.white",
            borderRadius: "100px",
            transition: "0.2s all linear",
            "&:hover": {
              backgroundColor: "primary.white",
              color: "primary.main",
            },
          }}
        >
          Grab Now
        </WpsIconButton>
      </Typography>
    </Stack>
  );
}
