const API_URL = 'http://127.0.0.1:8000/api';

export async function submitServiceRequest(data: {
  first_name: string;
  last_name: string;
  email: string;
  phone_number: string;
  service_type: string;
  message?: string;
  preferred_date?: string;
  preferred_time?: string;
  urgency?: string;
}) {
  const response = await fetch(`${API_URL}/service-requests`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    },
    body: JSON.stringify(data),
  });

  if (!response.ok) {
    const error = await response.json();
    throw new Error(error.message || 'Erreur lors de l\'envoi');
  }

  return response.json();
}