"use client";

import { Component } from "react";

class ErrorBoundary extends Component {
  constructor(props) {
    super(props);
    this.state = { hasError: false };
  }

  static getDerivedStateFromError(error) {
    return { hasError: true };
  }

  componentDidCatch(error, errorInfo) {
    // Aquí puedes realizar acciones con el error, como enviarlo a un servicio de seguimiento
    console.error("Error capturado:", error);
    console.error("Información del error:", errorInfo);
  }

  render() {
    if (this.state.hasError) {
      return <h1>Algo salió mal. Lo sentimos por las molestias.</h1>;
    }

    return this.props.children;
  }
}

export default ErrorBoundary;
