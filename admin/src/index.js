import React from "react";
import { createRoot } from "react-dom/client";
import ButtonModule from "@/modules/ButtonModule";
import IconButtonModule from "@/modules/IconButtonModule";
import WpsOfferStrip from "@/modules/WpsOfferStrip";
import Typography from "@mui/material/Typography";
import Stack from "@mui/material/Stack";
import Box from "@mui/material/Box";
import { ThemeProvider, createTheme } from "@mui/material/styles";

// ✅ Create a proper theme using createTheme
const theme = createTheme({
  palette: {
    primary: {
      main: "#0B0925",
      dark: "#000000",
      white: "#ffffff",
      darkWhite: "#f9f9f9",
      grey: "#DCDCDC",
      blue: "#2271B1",
      purple: "#0B0925",
    },
  },
});

function App({ title = "WPS Boiler Plate" }) {
  return (
    <ThemeProvider theme={theme}>
      <WpsOfferStrip sx={{ marginLeft: "-20px" }} />
      <Box
        sx={{
          boxShadow: "0px 4px 10px rgba(0,0,0,0.2)",
          m: 4,
          ml: 1,
          borderRadius: 5,
          bgcolor: "primary.darkWhite",
          "&:hover": {
            bgcolor: "primary.white",
          },
          p: 3,
        }}
      >
        <Typography
          variant="h5"
          component="h1"
          sx={{
            mb: 4,
            fontWeight: "bold",
          }}
        >
          {title}
        </Typography>
        <Stack spacing={4}>
          <ButtonModule />
          <IconButtonModule />
        </Stack>
      </Box>
    </ThemeProvider>
  );
}

const el = document.getElementById("wpsbp-admin-app");
if (el) createRoot(el).render(<App />);
