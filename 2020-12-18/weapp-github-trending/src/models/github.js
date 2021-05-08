import Base from './base'
import { Base64 } from "../common/libs/base64";

class Github extends Base
{
    getTrending (data = {},allowCache = true) {
        return this.get('/github/trending', data, allowCache)
    }

    getReadme (ns, name) {
        return this.get(`/github/${ns}/${name}/readme`).then((res) => {
            res.data.content = Base64.decode(res.data.content)
            return res
        })
    }
}

export default new Github()