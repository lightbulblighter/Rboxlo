const http = require('http')
const https = require('https')
const crypto = require('crypto')
const fs = require('fs').promises
const { exec } = require('child_process')

const config = require('./config.json')

function send(body, status, res) {	
	console.log(body)
	return res.writeHead(status).end(body)
}

function makeRequest(input, options) {
	const headers = { 'Content-Type': 'application/json', 'User-Agent': config.userAgent }
	
	if (options.headers) {
		options.headers = Object.assign(options.headers, headers)
	} else {
		options.headers = headers
	}
		
	const httpReq = https.request(options, httpRes => {
		httpRes.on('data', data => {
			process.stdout.write(data)
		})
	})

	httpReq.on('error', error => {
		console.error(error)
	})

	httpReq.write(input)
	httpReq.end()
}

function updateStatus(status, head) {
	makeRequest(JSON.stringify({...status, context: 'rboxlo/build'}), {
		hostname: 'api.github.com',
		port: 443,
		path: `/repos/${config.repo.creator}/${config.repo.name}/statuses/${head}`,
		method: 'POST',
		headers: {'Accept': 'application/vnd.github.v3+json', 'Authorization': `token ${config.historyRepo.token}`}
	})
}

http.createServer((req, res) => {
	if (req.method !== 'POST') return send('method not allowed', 405, res)

	let body = ''
	let sig = ''
	
	req.on('data', chunk => {
		sig = 'sha1=' + crypto.createHmac('sha1', config.repo.secret).update(chunk.toString()).digest('hex')
		body += chunk.toString()
	})
	
	req.on('end', () => {
		let head
		
		if (!req.headers['x-hub-signature'] || !req.headers['x-github-event']) {
			return send('missing headers', 400, res)
		} else if (!body) {
			return send('missing body', 400, res)
		} else if (req.headers['x-hub-signature'] !== sig) {
			return send('bad signature', 400, res)
		} else if (req.headers['x-github-event'] === 'ping') {
			return res.end()
		} else if (req.headers['x-github-event'] !== 'push') {
			return send('unknown event', 400, res)
		}
		
		try {
			head = JSON.parse(body).head_commit.id
		} catch (e) {
			return send('bad json', 400, res)
		}
		
		updateStatus({'state': 'pending'}, head)
		
		const git = exec('./build.sh')

		let content = `Started at ${new Date().toISOString()}.\n`

		git.stdout.on('data', (data) => {
			content += data
			console.log(data.toString())
		})

		git.stderr.on('data', (data) => {
			content += data
			console.log(data.toString())
		})

		git.on('close', async (code) => {
			content += `Ended at ${new Date().toISOString()}.`
			let intro = ''
			let status = 'success'
			let ping = `&${config.roles.success}`
			let description = 'Your image was successfully built.'
			
			if (code) {
				intro = `child process exited with code ${code}\n`
				status = 'failure'
				ping = `&${config.roles.failure}`
				description = 'Your image failed to build.'
			}
			
			content = `${intro}${content}`
			
			const date = new Date()
			
			const datePart = `${date.getFullYear()}/${date.getMonth()+1}/${date.getDate()}/`
			const name = `${head}.txt`
			const dir = config.historyRepo.local
			
			await fs.mkdir(`${dir}${datePart}`, { recursive: true }).catch(console.error)
			await fs.writeFile(`${dir}${datePart}${name}`, content)
			
			const finish = exec(`cd ${config.historyRepo.local} && git add . && git commit -m "Build ${status}" && git push`)
		
			finish.stdout.on('data', (data) => console.log(data))
			finish.stderr.on('data', (data) => console.log(data))
			finish.on('close', (finishCode) => {
				console.log(`child process exited with code ${finishCode}`)
				
				const url = `https://github.com/${config.historyRepo.creator}/${config.historyRepo.name}/blob/master/${datePart}${name}`
				
				updateStatus({'state': status, 'target_url': url, description}, head)
				
				makeRequest(JSON.stringify({content: `<@${ping}> ${description} <${url}>`}), {
					hostname: 'discordapp.com',
					port: 443,
					path: `/api/webhooks/${config.webhook.id}/${config.webhook.token}`,
					method: 'POST'
				})
			})
		})
		
		res.end()
	})
}).listen(3000)

console.log("Go!")