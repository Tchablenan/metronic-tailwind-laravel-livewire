export interface AppointmentFormData {
	first_name: string;
	last_name: string;
	email: string;
	phone_number: string;
	service_type: string;
	message: string;
	preferred_date: string;
	preferred_time: string;
	urgency: 'low' | 'medium' | 'high';
}
