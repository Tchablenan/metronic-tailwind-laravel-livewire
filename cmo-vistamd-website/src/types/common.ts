export interface LoaderProps {
	onComplete?: () => void
	duration?: number
}

export interface ApiResponse {
	success: boolean
	message: string
	data?: {
		reference: number
		email: string
	}
	errors?: Record<string, string[]>
}
